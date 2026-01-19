<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\IncompleteOrder;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderSuccessNotification;

class OrderController extends Controller
{
    /**
     * Get paginated and filtered customer orders
     */
    public function index(Request $request)
    {
        try {
            // Ensure user is authenticated
            $user = Auth::user();

            // Pagination settings
            $per_page = $request->input('per_page', 30);
            $page = $request->input('page', 1);

            // Start with base query for user's orders
            $query = Order::where('user_id', $user->id);

            // Filter by order status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filter by date range
            if ($request->has('start_date')) {
                $query->where('created_at', '>=', $request->input('start_date'));
            }
            if ($request->has('end_date')) {
                $query->where('created_at', '<=', $request->input('end_date'));
            }

            // Filter by minimum or maximum total
            if ($request->has('min_total')) {
                $query->where('total', '>=', $request->input('min_total'));
            }
            if ($request->has('max_total')) {
                $query->where('total', '<=', $request->input('max_total'));
            }

            // Search by order number or notes
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function (Builder $q) use ($searchTerm) {
                    $q->where('order_number', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('notes', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Include related models if requested
            $with = $request->input('with', []);

            // Ensure with is an array
            $with = is_string($with) ? explode(',', $with) : $with;

            // Validate and filter allowed relationships
            $allowedRelationships = [
                'items',
                'payment',
                'shipping_address'
            ];

            $validWith = array_intersect($with, $allowedRelationships);

            if (!empty($validWith)) {
                $query->with($validWith);
            }

            // Paginate results
            $results = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'results' => $results,
                'filter' => $request->all(),
                'available_relationships' => $allowedRelationships
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single order by ID
     */
    public function show($id)
    {
        try {
            // Ensure user is authenticated
            $user = Auth::user();

            // Find the order and ensure it belongs to the current user
            $order = Order::where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found',
                    'error_code' => 'ORDER_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string',
            'delivery_area' => 'required|in:inside_dhaka,outside_dhaka',
            'payment_method' => 'required|string|in:online,cash_on_delivery',
            'payment_method_gateway' => $request->payment_method === 'online' ? 'required|string' : 'nullable',
            'transaction_id' => $request->payment_method === 'online' ? 'required|string' : 'nullable',
            'cart' => 'required|array',
        ]);

        // Get delivery charges from settings
        $setting = Setting::first();
        $shipping_cost_inside_dhaka = $setting->delivery_charge;
        $shipping_cost_outside_dhaka = $setting->delivery_charge_outside_dhaka;

        try {
            DB::beginTransaction();

            $carts = $request->cart;

            // Check if cart is empty
            if (empty($carts)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Your cart is empty.',
                    'error_code' => 'CART_EMPTY'
                ], 400);
            }

            $user = $request->user();
            $subtotal = 0;
            $items = [];

            // Process and validate each cart item
            foreach ($carts as $key => &$cartItem) {
                if (!isset($cartItem['product_id'], $cartItem['quantity'])) {
                    unset($carts[$key]);
                    continue;
                }

                $product = Product::find($cartItem['product_id']);
                if (!$product) {
                    unset($carts[$key]);
                    continue;
                }

                $variation = null;
                if (!empty($cartItem['variation_id'])) {
                    $variation = ProductVariation::find($cartItem['variation_id']);
                }

                $quantity = (int) $cartItem['quantity'];

                // Use variation price if available, otherwise use product price
                $basePrice = $variation?->price ?? $product->price ?? 0;

                // Apply discount from product
                $discountType = $product->discount_type ?? null;
                $discountValue = $product->discount ?? 0;

                $discountedPrice = calculateDiscountedPrice($basePrice, $discountType, $discountValue);
                $totalPrice = $discountedPrice * $quantity;

                $items[] = [
                    'product_id' => $product->id,
                    'variation_id' => $variation?->id,
                    'quantity' => $quantity,
                    'price' => $discountedPrice,
                    'total' => $totalPrice,
                ];

                $subtotal += $totalPrice;
            }

            // If cart was modified (invalid items removed), update session
            session()->put('cart', $carts);

            // Check again if cart is empty after validation
            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No valid products in cart.',
                    'error_code' => 'CART_EMPTY'
                ], 400);
            }

            // Determine delivery charge
            $deliveryCharge = $request->delivery_area === 'inside_dhaka'
                ? $shipping_cost_inside_dhaka
                : $shipping_cost_outside_dhaka;

            $total = $subtotal + $deliveryCharge;

            // Apply coupon if exists
            $discount = 0;
            $coupon = null;

            if (session()->has('checkout_coupon')) {
                $coupon = Coupon::where('code', session('checkout_coupon'))->first();

                if ($coupon) {
                    $discount = $coupon->discount_type === 'amount'
                        ? $coupon->discount
                        : ($total * $coupon->discount) / 100;

                    $total -= $discount;
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user?->id,
                'total' => $total,
                'delivery_charge' => $deliveryCharge,
                'subtotal' => $subtotal,
                // 'delivery_area' => $request->delivery_area,
                'discount' => $discount,
                'coupon_id' => $coupon?->id,
                'status' => 1,
                'payment_method' => $request->payment_method,
                // 'payment_method_gateway' => $request->payment_method_gateway,
                'customer_name' => $request->name,
                'customer_phone_number' => $request->phone_number,
                'customer_address' => $request->address,
                'payment_transaction_id' => $request->transaction_id,
            ]);

            // Create order items
            foreach ($items as $item) {
                $order->items()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            // Clean up incomplete order if exists
            if (session()->has('incomplete_order_id')) {
                try {
                    $incompleteOrderId = session()->get('incomplete_order_id');
                    $incompleteOrder = IncompleteOrder::find($incompleteOrderId);
                    if ($incompleteOrder) {
                        $incompleteOrder->delete();
                    }
                } catch (\Throwable $th) {
                    Log::error('Failed to delete incomplete order: ' . $th->getMessage());
                }
            }

            // Clean up sessions
            session()->forget(['cart', 'checkout_coupon', 'incomplete_order_id']);

            // Notify user
            if ($user) {
                try {
                    Notification::send($user, new OrderSuccessNotification($order));
                } catch (\Throwable $th) {
                    Log::error('Notification error: ' . $th->getMessage());
                    // Continue processing even if notification fails
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => $order
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Order placement error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to place order. Please try again.',
            ], 400);
        }
    }
}
