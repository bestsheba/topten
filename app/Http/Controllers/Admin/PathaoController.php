<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Enums\CourierList;
use App\Models\PathaoOrder;
use Illuminate\Http\Request;
use App\Models\PathaoSetting;
use App\Services\PathaoService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PathaoController extends Controller
{
    protected $pathaoService;

    public function __construct(PathaoService $pathaoService)
    {
        $this->pathaoService = $pathaoService;
    }

    /**
     * Display Pathao settings page
     */
    public function settings()
    {
        if (!userCan('manage settings')) {
            abort(403);
        }

        $pathaoSettings = PathaoSetting::getActiveSettings();
        return view('admin.pages.pathao.settings', compact('pathaoSettings'));
    }

    /**
     * Update Pathao settings
     */
    public function updateSettings(Request $request)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }

        $request->validate([
            'environment' => 'required|in:sandbox,production',
            'base_url' => 'required|url',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'username' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $settings = PathaoSetting::first();

            if (!$settings) {
                $settings = new PathaoSetting();
            }

            $settings->fill($request->all());
            $settings->is_active = true;
            $settings->save();

            // Clear existing tokens to force re-authentication
            $settings->update([
                'access_token' => null,
                'refresh_token' => null,
                'token_expires_at' => null
            ]);

            flashMessage('success', 'Pathao settings updated successfully!');
        } catch (\Exception $e) {
            flashMessage('error', 'Failed to update settings: ' . $e->getMessage());
        }

        return redirect()->route('admin.pathao.settings');
    }

    /**
     * Test Pathao connection
     */
    public function testConnection()
    {
        if (!userCan('manage settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $token = $this->pathaoService->getAccessToken();
            $cities = $this->pathaoService->getCities();

            return response()->json([
                'success' => true,
                'message' => 'Connection successful! Found ' . count($cities) . ' cities.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a store in Pathao
     */
    public function createStore(Request $request)
    {
        if (!userCan('manage settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'contact_name' => 'required|string|min:3|max:50',
            'contact_number' => 'required|string|size:11',
            'address' => 'required|string|min:15|max:120',
            'city_id' => 'required|integer',
            'zone_id' => 'required|integer',
            'area_id' => 'required|integer',
            'secondary_contact' => 'nullable|string|size:11',
            'otp_number' => 'nullable|string|size:11',
        ]);

        try {
            $storeData = [
                'name' => $request->name,
                'contact_name' => $request->contact_name,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'city_id' => $request->city_id,
                'zone_id' => $request->zone_id,
                'area_id' => $request->area_id,
            ];

            if ($request->secondary_contact) {
                $storeData['secondary_contact'] = $request->secondary_contact;
            }

            if ($request->otp_number) {
                $storeData['otp_number'] = $request->otp_number;
            }

            $response = $this->pathaoService->createStore($storeData);

            // Update settings with store information
            $settings = PathaoSetting::getActiveSettings();
            if ($settings) {
                $settings->update([
                    'store_name' => $request->name,
                    'store_address' => $request->address,
                ]);

                // Try to fetch store ID after creation (for immediate approval)
                try {
                    $stores = $this->pathaoService->getStores();
                    if (!empty($stores)) {
                        // Find our store by name
                        $ourStore = collect($stores)->firstWhere('store_name', $request->name);
                        if ($ourStore && $ourStore['store_id']) {
                            $settings->update(['store_id' => $ourStore['store_id']]);
                        }
                    }
                } catch (\Exception $e) {
                    // Store might not be approved yet, that's okay
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Store created successfully! ' . ($response['message'] ?? 'Please wait for approval if store ID is not set.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create store: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update store ID manually
     */
    public function updateStoreId(Request $request)
    {
        if (!userCan('manage settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'store_id' => 'required|integer'
        ]);

        try {
            $settings = PathaoSetting::getActiveSettings();
            if ($settings) {
                $settings->update(['store_id' => $request->store_id]);
                return response()->json([
                    'success' => true,
                    'message' => 'Store ID updated successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pathao settings not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update store ID: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch and update stores from Pathao API
     */
    public function fetchStores()
    {
        if (!userCan('manage settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $stores = $this->pathaoService->getStores();
            return response()->json([
                'success' => true,
                'stores' => $stores,
                'message' => 'Stores fetched successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync store information from Pathao API
     */
    public function syncStore()
    {
        if (!userCan('manage settings')) {
            abort(403);
        }

        try {
            $stores = $this->pathaoService->getStores();

            if (empty($stores)) {
                throw new \Exception('No stores found on your Pathao account. Please create a store on the Pathao dashboard first.');
            }

            $store = $stores[0]; // Get the first store

            $settings = PathaoSetting::getActiveSettings();
            if ($settings) {
                $settings->update([
                    'store_id' => $store['store_id'],
                    'store_name' => $store['store_name'],
                    'store_address' => $store['store_address'],
                ]);

                flashMessage('success', 'Pathao store information synced successfully!');
            } else {
                throw new \Exception('Pathao settings not found');
            }
        } catch (\Exception $e) {
            flashMessage('error', 'Failed to sync store: ' . $e->getMessage());
        }

        return redirect()->route('admin.pathao.settings');
    }

    /**
     * Display orders with Pathao integration
     */
    public function orders()
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        $orders = Order::with(['pathaoOrder'])
            ->whereHas('pathaoOrder')
            ->latest()
            ->paginate(20);

        return view('admin.pages.pathao.orders', compact('orders'));
    }

    /**
     * Create single Pathao order
     */
    public function createSingleOrder(Request $request)
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|size:11',
            'recipient_address' => 'required|string|max:220',
            'item_weight' => 'required|numeric|min:0.5|max:10',
            'amount_to_collect' => 'required|numeric|min:0',
            'delivery_type' => 'required|in:48,12',
            'item_type' => 'required|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);
            $settings = PathaoSetting::getActiveSettings();

            if (!$settings) {
                throw new \Exception('Pathao settings not configured');
            }

            if (!$settings->store_id) {
                throw new \Exception('Pathao Store ID is not configured. Please configure your store first in Pathao settings.');
            }

            $orderData = [
                'store_id' => $settings->store_id,
                'merchant_order_id' => $order->hashed_id,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'delivery_type' => $request->delivery_type,
                'item_type' => $request->item_type,
                'item_quantity' => 1,
                'item_weight' => $request->item_weight,
                'amount_to_collect' => $order->total,
                'item_description' => $request->item_description ?? 'Order from ' . config('app.name'),
                'special_instruction' => $request->special_instruction,
            ];

            $response = $this->pathaoService->createOrder($orderData);

            // Save Pathao order to database
            $pathaoOrder = new PathaoOrder([
                'order_id' => $order->id,
                'merchant_order_id' => $order->hashed_id,
                'consignment_id' => $response['data']['consignment_id'],
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'delivery_type' => $request->delivery_type,
                'item_type' => $request->item_type,
                'item_quantity' => 1,
                'item_weight' => $request->item_weight,
                'amount_to_collect' => $order->total,
                'item_description' => $request->item_description ?? 'Order from ' . config('app.name'),
                'special_instruction' => $request->special_instruction,
                'delivery_fee' => $response['data']['delivery_fee'],
                'order_status' => $response['data']['order_status'],
                'response_data' => $response
            ]);

            $pathaoOrder->save();

            $consignment_id = isset($response['data']['consignment_id']) ? $response['data']['consignment_id'] : null;
            $tracking_url = 'https://merchant.pathao.com/tracking?consignment_id=' . $consignment_id . '&phone=' . $request->recipient_phone;

            $order->update([
                'courier' => CourierList::PATHAO->value,
                'courier_status' => $response['data']['order_status'],
                'courier_tracking_url' => $tracking_url,
            ]);

            DB::commit();

            flashMessage('success', 'Pathao order created successfully! Consignment ID: ' . $response['data']['consignment_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            flashMessage('error', 'Failed to create Pathao order: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Create bulk Pathao orders
     */
    public function createBulkOrders(Request $request)
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        $request->validate([
            'selected_ids' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $selectedIds = explode(',', $request->selected_ids);
            $orders = Order::whereIn('id', $selectedIds)->get();
            $settings = PathaoSetting::getActiveSettings();

            if (!$settings) {
                throw new \Exception('Pathao settings not configured');
            }

            if (!$settings->store_id) {
                throw new \Exception('Pathao Store ID is not configured. Please configure your store first in Pathao settings.');
            }

            $bulkOrders = [];
            foreach ($orders as $order) {
                $orderData = [
                    'store_id' => $settings->store_id,
                    'merchant_order_id' => $order->hashed_id,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->customer_phone_number,
                    'recipient_address' => $order->customer_address,
                    'delivery_type' => 48, // Normal Delivery
                    'item_type' => 2, // Parcel
                    'item_quantity' => $order->items()->count(),
                    'item_weight' => 0.5, // Default weight
                    'amount_to_collect' => $order->total, // No COD
                    'item_description' => 'Order from ' . config('app.name'),
                    'special_instruction' => '',
                ];

                $response = $this->pathaoService->createOrder($orderData);
                $orderData[] = $orderData;

                $consignment_id = $response['data']['consignment_id'] ?? null;
                $tracking_url = $consignment_id ? ('https://merchant.pathao.com/tracking?consignment_id=' . $consignment_id . '&phone=' . $order->customer_phone_number) : null;

                $pathaoOrder = new \App\Models\PathaoOrder([
                    'order_id' => $order->id,
                    'merchant_order_id' => $order->hashed_id,
                    'consignment_id' => $consignment_id,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->customer_phone_number,
                    'recipient_address' => $order->customer_address,
                    'delivery_type' => 48, // Normal Delivery
                    'item_type' => 2, // Parcel
                    'item_quantity' => $order->items()->count(),
                    'item_weight' => 0.5, // Default weight
                    'amount_to_collect' => $order->total, // No COD
                    'item_description' => 'Order from ' . config('app.name'),
                    'special_instruction' => '',
                    'delivery_fee' => $response['data']['delivery_fee'] ?? null,
                    'order_status' => $response['data']['order_status'] ?? null,
                    'response_data' => $response['data']
                ]);
                $pathaoOrder->save();

                $order->update([
                    'courier' => CourierList::PATHAO->value,
                    'courier_status' => $response['data']['order_status'] ?? null,
                    'courier_tracking_url' => $tracking_url,
                ]);
            }

            DB::commit();

            flashMessage('success', 'Bulk Pathao orders created successfully! ' . count($bulkOrders) . ' orders submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            flashMessage('error', 'Failed to create bulk Pathao orders: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Get cities for AJAX request
     */
    public function getCities()
    {
        try {
            $cities = $this->pathaoService->getCities();
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get zones for AJAX request
     */
    public function getZones(Request $request)
    {
        try {
            $zones = $this->pathaoService->getZones($request->city_id);
            return response()->json($zones);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get areas for AJAX request
     */
    public function getAreas(Request $request)
    {
        try {
            $areas = $this->pathaoService->getAreas($request->zone_id);
            return response()->json($areas);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calculate delivery price
     */
    public function calculatePrice(Request $request)
    {
        try {
            $settings = PathaoSetting::getActiveSettings();

            $priceData = [
                'store_id' => $settings->store_id,
                'item_type' => $request->item_type,
                'delivery_type' => $request->delivery_type,
                'item_weight' => $request->item_weight,
                'recipient_city' => $request->recipient_city,
                'recipient_zone' => $request->recipient_zone,
            ];

            $price = $this->pathaoService->calculatePrice($priceData);
            return response()->json($price);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Sync Pathao order status
     *
     * @param \App\Models\Order $order
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncStatus(Order $order, Request $request)
    {
        if (!$order->pathaoOrder) {
            return redirect()->back()->with('error',  'No Pathao order found for this order');
        }

        try {
            // Get order status from Pathao API
            $response = $this->pathaoService->getOrderInfo($order->pathaoOrder->consignment_id);

            if ($response['code'] === 200 && isset($response['data']['order_status'])) {
                // Update local status
                $order->pathaoOrder->update([
                    'order_status' => $response['data']['order_status'],
                    'response_data' => $response['data']
                ]);

                return redirect()->back()->with('success', 'Status updated successfully');
            }

            return redirect()->back()->with('error', 'Failed to fetch status from Pathao');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error syncing status: ' . $e->getMessage());
        }
    }
}
