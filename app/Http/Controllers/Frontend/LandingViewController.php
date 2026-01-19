<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Setting;
use App\Helpers\LandingPageConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandingViewController extends Controller
{
    public function landingPage($url)
    {
        $landing_page = LandingPage::active()->where('url', $url)->first();
        if (!$landing_page) {
            abort(404);
        }

        // Get config or use default
        $config = $landing_page->config ?? LandingPageConfig::getDefaultConfig();
        $sections = LandingPageConfig::getEnabledSections($config);
        $common = $config['common'] ?? [];

        return view('frontend.pages.landing-view', compact('landing_page', 'config', 'sections', 'common'));
    }

    public function storeOrder(Request $request, $url)
    {
        // Validate landing page exists
        $landing_page = LandingPage::active()->where('url', $url)->first();
        if (!$landing_page) {
            return back()->with('error', 'Landing page not found!');
        }

        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|string|in:online,cash_on_delivery',
        ]);

        try {
            DB::beginTransaction();

            // Get config
            $config = $landing_page->config ?? LandingPageConfig::getDefaultConfig();
            $checkoutSection = collect($config['sections'] ?? [])->firstWhere('type', 'checkout');
            $checkoutData = $checkoutSection['data'] ?? [];

            // Get product info from config
            $productName = $checkoutData['product_name'] ?? 'Landing Page Product';
            $productPrice = floatval($checkoutData['product_price'] ?? 0);

            // Get total price from config (this will be the order total - final amount)
            $total = floatval($checkoutData['total_price'] ?? $checkoutData['product_price'] ?? 0);

            if ($total <= 0) {
                return back()->with('error', 'Invalid total price!');
            }

            // For landing page orders, total_price is the final order total
            // Delivery charge is already included in the total_price or set to 0
            $deliveryCharge = 0;

            // Check if delivery is free from config text
            $deliveryChargeText = $checkoutData['delivery_charge'] ?? '';
            if (stripos($deliveryChargeText, 'বিনামূল্যে') !== false || stripos($deliveryChargeText, 'free') !== false) {
                $deliveryCharge = 0;
            }

            // Subtotal equals total (since delivery is free or included)
            $subtotal = $total;

            // Use product_price for display
            if ($productPrice <= 0) {
                $productPrice = $subtotal;
            }

            // Get packages from config
            $packagesSection = collect($config['sections'] ?? [])->firstWhere('type', 'packages');
            $packages = $packagesSection['data']['packages'] ?? [];

            // Collect all valid product IDs from packages
            $packageProducts = [];
            if (!empty($packages)) {
                foreach ($packages as $package) {
                    if (isset($package['product_id']) && $package['product_id']) {
                        $product = Product::find($package['product_id']);
                        if ($product) {
                            $packageProducts[] = [
                                'product_id' => $product->id,
                                'name' => $package['name'] ?? $product->name,
                                'price' => $product->final_price ?? $product->price ?? 0,
                            ];
                        }
                    }
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'delivery_charge' => $deliveryCharge,
                'subtotal' => $subtotal,
                'delivery_area' => 'inside_dhaka', // Default for landing page
                'discount' => 0,
                'coupon_id' => null,
                'status' => 1,
                'payment_method' => $request->payment_method,
                'paid_amount' => 0, // COD orders start with 0, will be updated when payment is made
                'customer_name' => $request->name,
                'customer_phone_number' => $request->phone_number,
                'customer_address' => $request->address,
                'payment_transaction_id' => $request->transaction_id ?? null,
                'is_from_landing_page' => true,
            ]);

            // Create order items for all packages
            if (!empty($packageProducts)) {
                // If we have multiple products, divide the subtotal equally
                $itemCount = count($packageProducts);
                $pricePerItem = $itemCount > 0 ? ($subtotal / $itemCount) : $subtotal;

                foreach ($packageProducts as $packageProduct) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'product_id' => $packageProduct['product_id'],
                        'variation_id' => null,
                        'quantity' => 1,
                        'price' => $pricePerItem,
                        'total' => $pricePerItem,
                    ]);
                }
            } else {
                // If no product_id in packages, create a single order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => null,
                    'variation_id' => null,
                    'quantity' => 1,
                    'price' => $subtotal,
                    'total' => $subtotal,
                ]);
            }

            DB::commit();

            // Refresh order to get hashed_id if it was generated
            $order->refresh();

            // Redirect to success page (use id, route will handle it)
            return redirect()->route('order.success', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Landing page order error: ' . $e->getMessage());
            return back()->with('error', 'Failed to place order. Please try again.')->withInput();
        }
    }
}
