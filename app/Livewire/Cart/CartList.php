<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Session;

class CartList extends Component
{
    public $carts = [];
    public $total = 0;
    public $discount = 0;

    protected $listeners = ['cart-updated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        $this->carts = $this->getCartItems();
        $this->calculateTotals();
    }

    private function getCartItems()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];

        foreach ($cart as $key => $item) {
            $product = Product::findOrFail($item['product']);
            $variation = $item['variation'] 
                ? ProductVariation::findOrFail($item['variation']) 
                : null;

            $cartItems[$key] = [
                'product' => $product,
                'variation' => $variation,
                'quantity' => $item['quantity'],
                'discounted_price' => $item['discounted_price'],
                'subtotal' => $item['subtotal']
            ];
        }

        return $cartItems;
    }

    public function changeQuantity($key, $action)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$key])) {
            if ($action === '+') {
                $cart[$key]['quantity']++;
            } elseif ($action === '-' && $cart[$key]['quantity'] > 1) {
                $cart[$key]['quantity']--;
            }

            // Recalculate subtotal
            $cart[$key]['subtotal'] = 
                $cart[$key]['quantity'] * $cart[$key]['discounted_price'];

            Session::put('cart', $cart);
            $this->updateCart();
        }
    }

    public function removeFromCart($key)
    {
        $cart = Session::get('cart', []);
        unset($cart[$key]);
        Session::put('cart', $cart);
        $this->updateCart();
    }

    private function calculateTotals()
    {
        $this->total = collect($this->carts)->sum('subtotal');
        // You can add coupon discount logic here if needed
        $this->discount = 0;
    }

    public function render()
    {
        return view('livewire.cart.cart-list', [
            'carts' => $this->carts,
            'total' => $this->total,
            'discount' => $this->discount
        ]);
    }
}
