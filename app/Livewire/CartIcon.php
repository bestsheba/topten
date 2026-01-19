<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ProductVariation;

class CartIcon extends Component
{
    public $items_in_user_cart;
    public $total;
    public $footer = false;

    public function  mount($footer = false)
    {
        $this->footer = $footer;

        $this->calculateCart();
    }

    #[On('update-cart-icon')]
    public function calculateCart()
    {
        $carts = session()->get('cart', []);

        $this->items_in_user_cart = count($carts);

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

            $price = $variation?->price ?? $product->final_price ?? 0;
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

        $this->total = $total;
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }

    public function openCartDrawer()
    {
        $this->dispatch('open-cart-drawer');
    }
}
