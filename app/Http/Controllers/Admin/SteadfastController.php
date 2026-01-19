<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\SteadfastOrder;
use App\Models\SteadfastSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\SteadfastService;
use App\Enums\CourierList;

class SteadfastController extends Controller
{
    protected SteadfastService $steadfastService;

    public function __construct(SteadfastService $steadfastService)
    {
        $this->steadfastService = $steadfastService;
    }

    /**
     * Display Steadfast orders in admin
     */
    public function orders()
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        $orders = Order::with(['steadfastOrder'])
            ->whereHas('steadfastOrder')
            ->latest()
            ->paginate(20);

        return view('admin.pages.steadfast.orders', compact('orders'));
    }

    /**
     * Display Steadfast settings page
     */
    public function settings()
    {
        if (!userCan('manage settings')) {
            abort(403);
        }

        $steadfastSettings = SteadfastSetting::first();
        return view('admin.pages.steadfast.settings', compact('steadfastSettings'));
    }

    /**
     * Update Steadfast settings
     */
    public function updateSettings(Request $request)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }

        $request->validate([
            'base_url' => 'required|url',
        ]);

        try {
            $settings = SteadfastSetting::first();

            if (!$settings) {
                $settings = new SteadfastSetting();
            }

            $settings->fill($request->only(['base_url', 'api_key', 'secret_key', 'webhook_bearer_token']))->save();
            $settings->update(['is_active' => true]);

            flashMessage('success', 'Steadfast settings updated successfully!');
        } catch (\Exception $e) {
            flashMessage('error', 'Failed to update Steadfast settings: ' . $e->getMessage());
        }

        return redirect()->route('admin.steadfast.settings');
    }

    /**
     * Test Steadfast connection using package facade
     */
    public function testConnection()
    {
        if (!userCan('manage settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $balance = $this->steadfastService->getCurrentBalance();
            return response()->json(['success' => true, 'message' => 'Connection successful', 'balance' => $balance]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create single Steadfast order using package facade
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
            'recipient_address' => 'required|string|max:250',
            'cod_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);

            $orderData = [
                'invoice' => $order->hashed_id ?? ($order->id . '-' . time()),
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'cod_amount' => $order->total,
                'note' => $request->note ?? null,
            ];

            // Use our Steadfast service
            $response = $this->steadfastService->placeOrder($orderData);

            $consignment = $response['consignment'] ?? null;

            $steadfastOrder = new SteadfastOrder([
                'order_id' => $order->id,
                'invoice' => $consignment['invoice'] ?? $orderData['invoice'],
                'consignment_id' => $consignment['consignment_id'] ?? null,
                'tracking_code' => $consignment['tracking_code'] ?? null,
                'recipient_name' => $orderData['recipient_name'],
                'recipient_phone' => $orderData['recipient_phone'],
                'recipient_address' => $orderData['recipient_address'],
                'cod_amount' => $orderData['cod_amount'],
                'status' => $consignment['status'] ?? null,
                'response_data' => $response,
            ]);

            $steadfastOrder->save();

            // update order courier fields (use enum value like Pathao controller)
            $order->update([
                'courier' => CourierList::STEADFAST->value,
                'courier_status' => $consignment['status'] ?? null,
                'courier_tracking_url' => isset($consignment['consignment_id']) ? ('https://portal.packzy.com/track/' . $consignment['consignment_id']) : null,
            ]);

            DB::commit();

            flashMessage('success', 'Steadfast order created successfully! Consignment ID: ' . ($consignment['consignment_id'] ?? '-'));
        } catch (\Exception $e) {
            DB::rollBack();
            flashMessage('error', 'Failed to create Steadfast order: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Create bulk Steadfast orders
     */
    public function createBulkOrders(Request $request)
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $orderIds = array_filter(explode(',', $request->selected_ids));
        if (empty($orderIds)) {
            flashMessage('error', 'No orders selected.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $orders = Order::whereIn('id', $orderIds)->get();

            $ordersData = [];
            foreach ($orders as $order) {
                $ordersData[] = [
                    'invoice' => $order->hashed_id ?? ($order->id . '-' . time()),
                    // Prefer explicit customer fields available on Order (match Pathao mapping)
                    'recipient_name' => $order->customer_name ?? ($order->userInfo?->name ?? 'N/A'),
                    'recipient_phone' => $order->customer_phone_number ?? ($order->userInfo?->phone ?? ''),
                    'recipient_address' => $order->customer_address ?? ($order->userInfo?->address ?? ''),
                    'cod_amount' => $order->total ?? 0,
                    'note' => $order->note ?? null,
                ];
            }

            // Log prepared orders payload for debugging
            Log::debug('Steadfast prepared bulk orders payload', [
                'count' => count($ordersData),
                'payload' => $ordersData,
            ]);


            $responses = $this->steadfastService->bulkCreateOrders($ordersData);

            // Debug log the API response for troubleshooting
            Log::debug('Steadfast bulkCreateOrders response', [
                'ordersData' => $ordersData,
                'api_response' => $responses,
            ]);

            // The API returns an envelope: ['status'=>..., 'message'=>..., 'data'=>[...] ]
            $items = $responses['data'] ?? $responses;

            if (!is_array($items)) {
                DB::rollBack();
                flashMessage('error', 'Failed to create bulk Steadfast orders: Invalid response from courier API.');
                return redirect()->back();
            }

            // reindex orders collection to numeric keys so indexes align with response array
            $orders = $orders->values();

            $successCount = 0;
            $failed = [];

            foreach ($items as $idx => $resp) {
                $order = $orders->get($idx);
                if (!$order) {
                    Log::warning('Steadfast response index has no matching order', ['idx' => $idx, 'resp' => $resp]);
                    continue;
                }

                // If API returned an error for this item, try to resolve common cases
                if (isset($resp['status']) && $resp['status'] === 'error') {
                    $errorMsg = $resp['error'] ?? '';
                    Log::warning('Steadfast item error', ['order_id' => $order->id, 'resp' => $resp]);

                    // If invoice already exists on Steadfast, try to fetch its details by invoice
                    if (!empty($resp['invoice']) && str_contains($errorMsg, 'THIS_INVOICE_ALREADY_EXISTS')) {
                        try {
                            $statusResp = $this->steadfastService->checkStatusByInvoice($resp['invoice']);
                            Log::debug('Resolved existing invoice via status_by_invoice', ['invoice' => $resp['invoice'], 'statusResp' => $statusResp]);

                            // Try to get consignment info from different possible shapes
                            $resolved = $statusResp['consignment'] ?? ($statusResp['data'][0] ?? $statusResp);

                            // If we have at least a consignment id or tracking code, save and update order
                            if (!empty($resolved['consignment_id']) || !empty($resolved['tracking_code'])) {
                                $steadfastOrder = new SteadfastOrder([
                                    'order_id' => $order->id,
                                    'invoice' => $resp['invoice'] ?? null,
                                    'consignment_id' => $resolved['consignment_id'] ?? null,
                                    'tracking_code' => $resolved['tracking_code'] ?? null,
                                    'recipient_name' => $resp['recipient_name'] ?? null,
                                    'recipient_phone' => $resp['recipient_phone'] ?? null,
                                    'recipient_address' => $resp['recipient_address'] ?? null,
                                    'cod_amount' => $resp['cod_amount'] ?? 0,
                                    'status' => $resolved['status'] ?? ($statusResp['delivery_status'] ?? null),
                                    'response_data' => $statusResp,
                                ]);
                                $steadfastOrder->save();

                                $order->update([
                                    'courier' => CourierList::STEADFAST->value,
                                    'courier_status' => $resolved['status'] ?? ($statusResp['delivery_status'] ?? null),
                                    'courier_tracking_url' => isset($resolved['consignment_id']) ? ('https://portal.packzy.com/track/' . $resolved['consignment_id']) : null,
                                ]);

                                $successCount++;
                                continue;
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to resolve existing invoice', ['invoice' => $resp['invoice'], 'error' => $e->getMessage()]);
                        }
                    }

                    // otherwise record as failed
                    $failed[] = ['order_id' => $order->id, 'error' => $errorMsg ?: 'unknown'];
                    continue;
                }

                $steadfastOrder = new SteadfastOrder([
                    'order_id' => $order->id,
                    'invoice' => $resp['invoice'] ?? null,
                    'consignment_id' => $resp['consignment_id'] ?? null,
                    'tracking_code' => $resp['tracking_code'] ?? null,
                    'recipient_name' => $resp['recipient_name'] ?? null,
                    'recipient_phone' => $resp['recipient_phone'] ?? null,
                    'recipient_address' => $resp['recipient_address'] ?? null,
                    'cod_amount' => $resp['cod_amount'] ?? 0,
                    'status' => $resp['status'] ?? null,
                    'response_data' => $resp,
                ]);
                $steadfastOrder->save();

                $order->update([
                    'courier' => CourierList::STEADFAST->value,
                    'courier_status' => $resp['status'] ?? null,
                    'courier_tracking_url' => isset($resp['consignment_id']) ? ('https://portal.packzy.com/track/' . $resp['consignment_id']) : null,
                ]);

                $successCount++;
            }

            if (!empty($failed)) {
                Log::info('Some Steadfast orders failed in bulk', ['failed' => $failed]);
                flashMessage('warning', 'Bulk processed with some errors. Check logs for details.');
            } else {
                flashMessage('success', 'Bulk Steadfast orders submitted. ' . $successCount . ' orders created.');
            }

            DB::commit();

            flashMessage('success', 'Bulk Steadfast orders submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            flashMessage('error', 'Failed to create bulk Steadfast orders: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Sync Steadfast order status for a given Order
     */
    public function syncStatus(Order $order, Request $request)
    {
        if (!$order->steadfastOrder) {
            return response()->json(['success' => false, 'message' => 'No Steadfast order found for this order'], 404);
        }

        try {
            $consignmentId = $order->steadfastOrder->consignment_id ?? null;

            if (!$consignmentId) {
                return redirect()->back()->with('error', 'No consignment id available');
            }

            $response = $this->steadfastService->checkDeliveryStatusByConsignmentId($consignmentId);

            $deliveryStatus = $response['delivery_status'] ?? null;

            $order->steadfastOrder->update([
                'status' => $deliveryStatus,
                'response_data' => $response,
            ]);

            return redirect()->back()->with('success', 'Status updated successfully');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error syncing status: ' . $e->getMessage()], 500);
        }
    }
}
