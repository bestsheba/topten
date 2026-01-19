@extends('frontend.layouts.app')
@section('title', 'Your Cart')

@section('content')
    <div class="container py-6">
        <h2 class="text-2xl font-semibold mb-6">Your Shopping Cart</h2>

        @if (count($carts))
            @foreach ($carts as $key => $cart)
                @php
                    $product = $cart['product'];
                    $variation = $cart['variation'] ?? null;
                    $imageUrl = $variation?->image ? asset('storage/' . $variation->image) : $product->picture_url;
                    $attributes = $variation?->attributeValues
                        ->map(fn($val) => $val->attribute->name . ': ' . $val->value)
                        ->join(', ');
                @endphp
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between bg-white shadow border rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-4 w-full md:w-1/2">
                        <img src="{{ asset($imageUrl) }}" alt="{{ $product->name }}"
                            class="w-20 h-20 object-cover rounded-md border" />
                        <div>
                            <h4 class="text-lg font-semibold">{{ $product->name }}</h4>
                            @if ($attributes)
                                <p class="text-sm text-gray-500">{{ $attributes }}</p>
                            @endif
                            <p class="text-sm text-gray-700 mt-1">
                                {{ showAmount($cart['discounted_price']) }} × {{ $cart['quantity'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-3 md:mt-0 md:w-auto">
                        <form action="{{ route('change.cart.qty') }}" method="POST" class="flex">
                            @csrf
                            <input type="hidden" name="cart" value="{{ $key }}">
                            <input type="hidden" name="event" value="-">
                            <button type="submit"
                                class="px-3 py-1 bg-gray-200 rounded-l hover:bg-gray-300 text-xl">-</button>
                        </form>
                        <span class="px-3">{{ $cart['quantity'] }}</span>
                        <form action="{{ route('change.cart.qty') }}" method="POST" class="flex">
                            @csrf
                            <input type="hidden" name="cart" value="{{ $key }}">
                            <input type="hidden" name="event" value="+">
                            <button type="submit"
                                class="px-3 py-1 bg-gray-200 rounded-r hover:bg-gray-300 text-xl">+</button>
                        </form>

                        <form action="{{ url('/cart/remove') }}" method="POST" class="ml-2">
                            @csrf
                            <input type="hidden" name="cart" value="{{ $key }}">
                            <button type="submit" class="text-red-500 text-sm hover:underline">Remove</button>
                        </form>

                    </div>

                    <div class="text-right font-semibold mt-3 md:mt-0 md:w-1/6">
                        {{ showAmount($cart['subtotal']) }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white p-6 rounded shadow text-center">
                <p class="text-gray-600">Your cart is currently empty.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block text-blue-500 hover:underline">
                    Continue Shopping
                </a>
            </div>
        @endif

        @if (count($carts))
            <div class="mt-6 bg-white p-5 shadow border rounded-xl">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-gray-700">
                        <span>Subtotal:</span>
                        <span>{{ showAmount($total + $discount) }}</span>
                    </div>
                    @if ($discount)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Coupon Discount:</span>
                            <span>-{{ showAmount($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold border-t pt-2 mt-2 text-base">
                        <span>Total:</span>
                        <span>{{ showAmount($total) }}</span>
                    </div>
                </div>

                <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="{{ route('shop') }}" class="text-sm text-blue-500 hover:underline">
                        ← Continue Shopping
                    </a>
                    <a href="{{ route('checkout') }}"
                        class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90">
                        Proceed to Checkout →
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
