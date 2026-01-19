<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Session;

class AddToCart extends Component
{
    public $product;
    public $variation_id = null;
    public $quantity = 1;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        dd(3);
        $cart = Session::get('cart', []);
        $cartKey = $this->generateCartKey();

        // Check if product is already in cart
        $existingCartItem = collect($cart)->first(function ($item, $key) use ($cartKey) {
            return $key === $cartKey;
        });

        if ($existingCartItem) {
            // Update quantity if product already exists
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            // Add new item to cart
            $cart[$cartKey] = $this->prepareCartItem();
        }

        Session::put('cart', $cart);
        $this->dispatch('cart-updated');
    }
    public function orderNow()
    {
        $cart = session()->get('cart', []);
        $cartKey = $this->product->id . ':' . ($this->selectedVariation?->id ?? 'default');

        // Always add/update the product in the cart
        $cart[$cartKey] = [
            'product_id' => $this->product->id,
            'variation_id' => $this->selectedVariation?->id,
            'quantity' => $this->quantity ?? 1,
        ];

        session()->put('cart', $cart);

        // Update the cart icon (Livewire event)
        $this->dispatch('update-cart-icon');

        // Redirect directly to checkout page
        return redirect()->route('checkout');
    }
    private function generateCartKey()
    {
        // Generate a unique key for the cart item
        return $this->product->id .
            ($this->variation_id ? '_' . $this->variation_id : '');
    }

    private function prepareCartItem()
    {
        $variation = $this->variation_id
            ? ProductVariation::findOrFail($this->variation_id)
            : null;

        $price = $variation ? $variation->price : $this->product->final_price;
        $discountedPrice = $this->calculateDiscountedPrice($price);

        return [
            'product' => $this->product->id,
            'variation' => $this->variation_id,
            'quantity' => $this->quantity,
            'price' => $price,
            'discounted_price' => $discountedPrice,
            'subtotal' => $discountedPrice * $this->quantity
        ];
    }

    private function calculateDiscountedPrice($price)
    {
        // Apply any product or variation specific discounts
        if ($this->product->special_offer) {
            return $price * (1 - $this->product->special_offer / 100);
        }
        return $price;
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
