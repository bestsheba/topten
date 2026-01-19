<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\ProductVariation;

class CartDrawer extends Component
{
    public $cartDrawerOpen = false;
    public $cartItems = [];

    public function mount()
    {
        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        $cart = Session::get('cart', []);
        $this->cartItems = [];

        foreach ($cart as $key => $item) {
            // Parse the key which is in format "product_id:variation_id"
            list($productId, $variationId) = explode(':', $key);

            $product = Product::find($productId);
            $variation = ProductVariation::find($variationId);

            $this->cartItems[$key] = [
                'product' => $product,
                'variation' => $variation,
                'quantity' => $item['quantity'],
                'price' => $variation?->price ?? $product['final_price']
            ];
        }
    }

    public function render()
    {
        return view('livewire.cart-drawer', [
            'cartItems' => $this->cartItems
        ]);
    }

    #[On('open-cart-drawer')]
    public function toggleDrawer()
    {
        $this->loadCartItems();
        $this->cartDrawerOpen = !$this->cartDrawerOpen;
    }

    public function removeFromCart($key)
    {
        $cart = Session::get('cart', []);
        unset($cart[$key]);
        Session::put('cart', $cart);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }

    public function updateQuantity($key, $change)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$key])) {
            // Ensure quantity doesn't go below 1
            $cart[$key]['quantity'] = max(1, $cart[$key]['quantity'] + $change);
            
            Session::put('cart', $cart);
            $this->loadCartItems();
            $this->dispatch('cart-updated');
        }
    }

    public function getTotalPrice()
    {
        return collect($this->cartItems)->sum(function($item) {
            // Use variation price if exists, otherwise use product price
            $price = $item['variation']?->price ?? $item['product']->final_price;
            return $price * $item['quantity'];
        });
    }
}
