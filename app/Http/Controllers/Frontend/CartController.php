<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Carbon;
use App\Models\IncompleteOrder;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Services\SslcommerzService;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderSuccessNotification;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $product = Product::find($request->product);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        // Retrieve variation (optional)
        $variation = null;
        if ($request->filled('variation_id')) {
            $variation = \App\Models\ProductVariation::with('attributeValues')->find($request->variation_id);

            if (!$variation || $variation->product_id != $product->id) {
                return redirect()->route('products.index')->with('error', 'Invalid product variation!');
            }
        }

        // Get cart from session
        $cart = session()->get('cart', []);
        $cartKey = $request->product . ':' . ($request->variation_id ?? 0);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);

            flashMessage('success', 'Product successfully removed from your cart.');
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variation_id' => $variation?->id,
                'quantity' => $request->quantity ?? 1,
            ];
            session()->put('cart', $cart);

            flashMessage('success', 'Product successfully added to your cart.');
        }

        $buyNow = $request->boolean('buy_now');

        if ($buyNow) {
            return redirect()->route('checkout');
        }

        return redirect()->back()->with('scroll_position', $request->scroll_position);
    }
    public function remove(Request $request)
    {
        $cartKey = $request->input('cart');
        $carts = session()->get('cart', []);

        if (isset($carts[$cartKey])) {
            unset($carts[$cartKey]);
            session()->put('cart', $carts);

            flashMessage('success', 'Item removed from cart.');
        } else {
            flashMessage('warning', 'Item not found in cart.');
        }

        return redirect()->back();
    }

    public function cart(Request $request)
    {
        $carts = session()->get('cart', []);

        if (empty($carts)) {
            flashMessage('warning', 'Your cart is empty.');
            return redirect('/');
        }

        $total = 0;
        foreach ($carts as $key => &$item) {
            if (!isset($item['product_id'], $item['quantity'])) {
                unset($carts[$key]);
                continue;
            }

            $product = Product::find($item['product_id']);
            if (!$product) {
                unset($carts[$key]);
                continue;
            }

            $variation = null;
            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::find($item['variation_id']);
            }

            $price = $variation?->price ?? $product->price ?? 0;
            $discountType = $product->discount_type ?? null;
            $discountValue = $product->discount ?? 0;

            $discountedPrice = calculateDiscountedPrice($price, $discountType, $discountValue);
            $quantity = (int) $item['quantity'];

            // Enrich cart item
            $item['product'] = $product;
            $item['variation'] = $variation;
            $item['discounted_price'] = $discountedPrice;
            $item['subtotal'] = $discountedPrice * $quantity;

            $total += $item['subtotal'];
        }

        $discount = 0;
        if (session()->has('checkout_coupon')) {
            $coupon = Coupon::where('code', session('checkout_coupon'))->first();

            if ($coupon) {
                $discount = $coupon->discount_type === 'amount'
                    ? $coupon->discount
                    : ($total * $coupon->discount / 100);

                $total -= $discount;
            }
        }

        return view('frontend.pages.user.cart', [
            'carts' => $carts,
            'total' => $total,
            'discount' => $discount,
        ]);
    }

    public function changeCartQty(Request $request)
    {
        $carts = session()->get('cart', []);
        if (isset($carts[$request->cart])) {
            if ($request->event === '+') {
                $carts[$request->cart]['quantity']++;
            } elseif ($request->event === '-') {
                if ($carts[$request->cart]['quantity'] > 1) {
                    $carts[$request->cart]['quantity']--;
                }
            }
            session()->put('cart', $carts);
        }
        flashMessage('success', 'Quantity successfully updated');
        return redirect()->back();
    }

    public function checkout(Request $request, CartService $cartService)
    {
        // ======================================== 
        $user = $cartService->getUserInfo()['user'];
        $address = $cartService->getUserInfo()['address'];
        // ======================================== 

        // ======================================== 
        $cartInfo = $cartService->getCartInfo();
        $carts = $cartInfo['items'];
        $subtotal = $cartInfo['subtotal'];
        $total = $cartInfo['total'];
        // ======================================== 

        // ======================================== 
        $server = $request->server();
        $pageWasRefreshed = isset($server['HTTP_CACHE_CONTROL']) && $server['HTTP_CACHE_CONTROL'] === 'max-age=0';
        if (!$pageWasRefreshed) {
            session()->forget('incomplete_order_id');
        }

        $setting = Setting::first();
        $inside_dhaka = $setting->delivery_charge;
        $outside_dhaka = $setting->delivery_charge_outside_dhaka;
        $sub_area = $setting->delivery_charge_sub_area;
        $inside_title = $setting->delivery_charge_inside_title ?? 'Inside Dhaka';
        $outside_title = $setting->delivery_charge_outside_title ?? 'Outside Dhaka';
        $sub_area_title = $setting->delivery_charge_sub_area_title ?? 'Sub Area';

        if (count($carts) == 0) {
            flashMessage('warning', 'No cart items');
            return redirect('/');
        }

        $discount = 0;
        // Apply coupon discount if exists
        if (session()->has('checkout_coupon')) {
            $coupon = Coupon::where('code', session()->get('checkout_coupon'))->first();

            if ($coupon) {
                if ($coupon->discount_type == 'amount') {
                    $discount = $coupon->discount;
                } elseif ($coupon->discount_type == 'percentage') {
                    $discount = ($subtotal * $coupon->discount) / 100;
                }

                $total = $subtotal - $discount;
            }
        }

        $incompleteOrderId = session()->get('incomplete_order_id');

        try {
            if (!$pageWasRefreshed && !$incompleteOrderId) {
                $incomplete = IncompleteOrder::create([
                    'products' => $carts,
                ]);
                $incompleteOrderId = $incomplete->id;
                session()->put('incomplete_order_id', $incompleteOrderId);
            }
        } catch (\Throwable $th) {
            // Log the error if needed
            // \Log::error($th->getMessage());
        }

        return view('frontend.pages.user.checkout', compact(
            'carts',
            'subtotal',
            'total',
            'address',
            'inside_dhaka',
            'outside_dhaka',
            'sub_area',
            'inside_title',
            'outside_title',
            'sub_area_title',
            'discount',
            'incompleteOrderId'
        ));
    }
    public function placeOrder(Request $request)
    {
        // Get available delivery areas
        $setting = Setting::first();
        $availableAreas = [];
        if ($setting->delivery_charge > 0) {
            $availableAreas[] = 'inside_dhaka';
        }
        if ($setting->delivery_charge_outside_dhaka > 0) {
            $availableAreas[] = 'outside_dhaka';
        }

        // If no delivery areas available, add no_delivery option
        if (empty($availableAreas)) {
            $availableAreas[] = 'no_delivery';
        }

        // Build allowed online gateways from settings to validate properly
        $allowedGateways = [];
        if ($setting->sslcommerz_enabled) {
            $allowedGateways[] = 'sslcommerz';
        }
        if ($setting->bkash_enabled) {
            $allowedGateways[] = 'bKash';
        }
        if ($setting->nagad_enabled) {
            $allowedGateways[] = 'Nagad';
        }
        if ($setting->rocket_enabled) {
            $allowedGateways[] = 'Rocket';
        }
        if ($setting->bank_enabled) {
            $allowedGateways[] = 'Bank Account';
        }
        if ($setting->stripe_enabled) {
            $allowedGateways[] = 'stripe';
        }

        $paymentGatewayRule = 'nullable';
        if ($request->payment_method === 'online') {
            if (!empty($allowedGateways)) {
                $paymentGatewayRule = 'required|string|in:' . implode(',', $allowedGateways);
            } else {
                // No online gateways allowed, force to invalid so validation fails
                $paymentGatewayRule = 'required|string|in:' . implode(',', ['__none__']);
            }
        }

        $transactionRule = 'nullable';
        if ($request->payment_method === 'online' && !in_array($request->payment_method_gateway, ['sslcommerz', 'stripe'])) {
            $transactionRule = 'required|string';
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string',
            'delivery_area' => 'required|in:' . implode(',', $availableAreas),
            'payment_method' => 'required|string|in:online,cash_on_delivery',
            'payment_method_gateway' => $paymentGatewayRule,
            'transaction_id' => $transactionRule,
        ]);

        // Get delivery charges from settings
        $setting = Setting::first();
        $shipping_cost_inside_dhaka = $setting->delivery_charge;
        $shipping_cost_outside_dhaka = $setting->delivery_charge_outside_dhaka;

        try {
            DB::beginTransaction();

            $carts = session()->get('cart', []);

            // Check if cart is empty
            if (empty($carts)) {
                flashMessage('warning', 'Your cart is empty.');
                return redirect('/');
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
                $basePrice = $variation?->price ?? $product->final_price ?? 0;

                $items[] = [
                    'product_id' => $product->id,
                    'variation_id' => $variation?->id,
                    'quantity' => $quantity,
                    'price' => $basePrice,
                    'total' => $basePrice * $quantity,
                ];

                $subtotal += $basePrice * $quantity;
            }

            // If cart was modified (invalid items removed), update session
            session()->put('cart', $carts);

            // Check again if cart is empty after validation
            if (empty($items)) {
                flashMessage('warning', 'No valid products in cart.');
                return redirect('/');
            }

            // Determine delivery charge
            $deliveryCharge = 0;
            if ($request->delivery_area === 'inside_dhaka' && $shipping_cost_inside_dhaka > 0) {
                $deliveryCharge = $shipping_cost_inside_dhaka;
            } elseif ($request->delivery_area === 'outside_dhaka' && $shipping_cost_outside_dhaka > 0) {
                $deliveryCharge = $shipping_cost_outside_dhaka;
            } elseif ($request->delivery_area === 'no_delivery') {
                $deliveryCharge = 0; // Explicitly set to 0 for no delivery
            }

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

            $total_product_discount = (new CartService())->getTotalDiscount();

            // Create order
            $order = Order::create([
                'user_id' => $user?->id,
                'total' => $total,
                'delivery_charge' => $deliveryCharge,
                'subtotal' => $subtotal,
                'delivery_area' => $request->delivery_area,
                'discount' => $discount + $total_product_discount,
                'coupon_id' => $coupon?->id,
                'status' => 1,
                'payment_method' => $request->payment_method,
                'paid_amount' => 0, // Will be updated by payment gateway callback for online payments
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
                    \Log::error('Failed to delete incomplete order: ' . $th->getMessage());
                }
            }

            // Notify user
            if ($user) {
                try {
                    Notification::send($user, new OrderSuccessNotification($order));
                } catch (\Throwable $th) {
                    \Log::error('Notification error: ' . $th->getMessage());
                    // Continue processing even if notification fails
                }
            }

            DB::commit();

            // check if payment method is online and method gateway is sslcommerz
            if ($request->payment_method === 'online' && $request->payment_method_gateway === 'sslcommerz') {
                return $this->sslcommerzRedirectUrl($request, $total, $user, $order);
            }

            // check if payment method is online and method gateway is stripe
            if ($request->payment_method === 'online' && $request->payment_method_gateway === 'stripe') {
                return $this->stripeCheckoutUrl($total, $user, $order);
            }

            $this->clearCart();

            flashMessage('success', 'Order placed successfully!');
            return redirect()->route('order.success', $order->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Order placement error: ' . $th->getMessage());
            flashMessage('error', 'Failed to place order. Please try again.');
            return redirect()->back();
        }
    }

    public function clearCart()
    {
        // Clean up sessions
        session()->forget(['cart', 'checkout_coupon', 'incomplete_order_id']);
    }

    public function sslcommerzRedirectUrl($request, $total, $user, $order)
    {
        try {
            $sslcommerzService = new SslcommerzService();
            $sslcommerz_output = $sslcommerzService->index($request, $total, $user, $order);

            // Normalize output to an associative array to avoid "stdClass as array" errors.
            if (is_string($sslcommerz_output)) {
                $sslcommerz_output = json_decode($sslcommerz_output, true);
            } elseif (is_object($sslcommerz_output)) {
                $sslcommerz_output = json_decode(json_encode($sslcommerz_output), true);
            }

            if (!is_array($sslcommerz_output) || !isset($sslcommerz_output['data']) || empty($sslcommerz_output['data'])) {
                info('failed to generate SSLCOMMERZ payment link. full output: ', [$sslcommerz_output]);
                $order->delete(); // Delete the order if payment initiation fails
                return redirect()->back()->with('error', 'SSLCommerz Payment initiation failed. Please try again.');
            }

            $sslcommerz_redirect_url = $sslcommerz_output['data'];

            $this->clearCart();
            return redirect($sslcommerz_redirect_url);
        } catch (\Throwable $th) {
            info('failed to generate SSLCOMMERZ payment link. ' . $th->getMessage());
            return redirect()->back()->with('error', 'SSLCommerz Payment initiation failed. Please try again.');
        }
    }

    public function stripeCheckoutUrl($total, $user, $order)
    {
        try {
            $stripeService = new StripeService();

            // Get cart items to pass to Stripe
            $carts = session()->get('cart', []);
            $items = [];

            foreach ($carts as $cartItem) {
                if (!isset($cartItem['product_id'], $cartItem['quantity'])) {
                    continue;
                }

                $product = Product::find($cartItem['product_id']);
                if (!$product) {
                    continue;
                }

                $variation = null;
                if (!empty($cartItem['variation_id'])) {
                    $variation = ProductVariation::find($cartItem['variation_id']);
                }

                $basePrice = $variation?->price ?? $product->final_price ?? 0;
                $quantity = (int) $cartItem['quantity'];

                $items[] = [
                    'name' => $product->name,
                    'price' => $basePrice,
                    'quantity' => $quantity,
                ];
            }

            // Create Stripe checkout session
            $session = $stripeService->createCheckoutSession($order, $items);

            if (!$session || !$session->url) {
                $order->delete();
                return redirect()->back()->with('error', 'Stripe Payment initiation failed. Please try again.');
            }

            $this->clearCart();
            return redirect($session->url);
        } catch (\Throwable $th) {
            Log::error('Stripe checkout error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Stripe Payment initiation failed. Please try again.');
        }
    }

    public function applyCoupon(Request $request)
    {
        // Handle coupon removal
        if (empty($request->coupon_code)) {
            session()->forget('checkout_coupon');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon removed successfully'
                ]);
            }

            flashMessage('success', 'Coupon removed successfully');
            return redirect()->back();
        }

        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon does not exist'
                ]);
            }
            flashMessage('warning', 'Coupon does not exist');
            return redirect()->back();
        }

        $coupon->start_date = Carbon::parse($coupon->start_date);

        if ($coupon->start_date->isFuture()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has not started yet'
                ]);
            }
            flashMessage('warning', 'Coupon has not started yet');
            return redirect()->back();
        }

        $coupon->expired_date = Carbon::parse($coupon->expired_date);

        if ($coupon->expired_date->isPast()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has expired'
                ]);
            }
            flashMessage('warning', 'Coupon has expired');
            return redirect()->back();
        }

        session()->put('checkout_coupon', $request->coupon_code);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully'
            ]);
        }

        flashMessage('success', 'Coupon applied successfully');
        return redirect()->back();
    }

    public function addIncompleteOrderData($incomplete_order, Request $request)
    {
        $column = $request->field;
        $value = $request->value;
        $incomplete = IncompleteOrder::find($incomplete_order);
        if ($incomplete) {
            $incomplete->$column = $value;
            $incomplete->save();
        }
        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully',
        ]);
    }

    public function orderSuccess($orderId)
    {
        // Try to find by ID first, then by hashed_id
        $order = Order::with(['items.product'])
            ->where(function ($query) use ($orderId) {
                $query->where('id', $orderId)
                    ->orWhere('hashed_id', $orderId);
            })
            ->firstOrFail();

        // Generate tracking URL using hashed_id
        $trackingUrl = route('track.order') . '?order_id=' . $order->hashed_id;

        return view('frontend.pages.user.order-success', compact('order', 'trackingUrl'));
    }
}
