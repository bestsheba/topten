<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getTotalDiscount()
    {
        $discount = 0;
        $carts = session()->get('cart', []);

        foreach ($carts as $key => &$item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->discount > 0) {
                $discount += $product->discount_amount * $item['quantity'];
            }
        }

        return $discount;
    }

    public function getUserInfo()
    {
        $user = Auth::user();
        $address = $user?->address;

        return [
            'user' => $address,
            'address' => $address,
        ];
    }

    public function getCarts()
    {
        $carts = session()->get('cart', []);
        return $carts;
    }

    public function getCartInfo()
    {
        $carts = $this->getCarts();
        $total = 0;
        $items = [];

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

            $quantity = (int) $item['quantity'];

            // Enrich cart item
            $item['product'] = $product;
            $item['variation'] = $variation;
            $item['subtotal'] = $this->getFinalPrice($product, $variation) * $quantity;

            $total += $item['subtotal'];
            $items[] = $item;
        }

        $subtotal = $total;

        return [
            'items' => $items,
            'total' => $total,
            'subtotal' => $subtotal,
        ];
    }

    public function getFinalPrice($product, $variation)
    {
        if ($variation) {
            return $variation->price;
        } else {
            return $product->final_price;
        }
    }
}
