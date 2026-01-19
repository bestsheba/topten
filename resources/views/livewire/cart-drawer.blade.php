<div x-data="{ open: @entangle('cartDrawerOpen') }">
    <div class="fixed w-full h-full inset-0 z-50" id="mobile-menu" x-description="Mobile menu" x-show="open"
        style="display: none;">
        <!-- bg open -->
        <span class="fixed bg-gray-900 opacity-70 w-full h-full top-0"></span>

        <!-- Mobile Cart Drawer -->
        <nav id="mobile-nav"
            class="flex ioajsklehsnm end-0 w-72 fixed top-0 py-4 bg-white border-l-2 border-primary h-full fioplahensmk z-40"
            x-show="open" @click.away="open=false" x-description="Mobile menu" role="menu"
            aria-orientation="vertical" aria-labelledby="navbartoggle"
            x-transition:enter="transform transition-transform duration-300" x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transform transition-transform duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

            <div class="w-full flex flex-col h-full">
                <!-- Cart Header -->
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Your Cart</h2>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="flex-grow overflow-y-auto px-4 py-2">
                    @if (count($cartItems) > 0)
                        @foreach ($cartItems as $key => $item)
                            <div class="flex items-center space-x-3 py-2 border-b border-gray-200">
                                <!-- Product Image -->
                                <img src="{{ $item['product']?->picture ? asset($item['product']->picture) : '' }}"
                                    alt="{{ $item['product']->name }}" class="w-16 h-16 object-cover rounded">

                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <h3 class="text-sm font-medium text-gray-800">
                                        {{ $item['product']->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        @if (isset($item['variation']) && $item['variation'])
                                            <div class="text-sm text-gray-500">
                                                {{ $item['variation']->attributeValues->map(fn($val) => $val->attribute->name . ': ' . $val->value)->join(', ') }}
                                            </div>
                                        @endif
                                    </p>
                                    <div class="flex justify-between items-center mt-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex border border-gray-300 text-gray-600 divide-x divide-gray-300 w-max">
                                                <div wire:click="updateQuantity('{{ $item['product']->id }}:{{ $item['variation']?->id }}', -1)"
                                                    class="h-6 w-6 text-base flex items-center justify-center cursor-pointer select-none hover:bg-gray-100">
                                                    -
                                                </div>
                                                <div class="h-6 w-6 text-xs flex items-center justify-center">
                                                    {{ $item['quantity'] }}
                                                </div>
                                                <div wire:click="updateQuantity('{{ $item['product']->id }}:{{ $item['variation']?->id }}', 1)"
                                                    class="h-6 w-6 text-base flex items-center justify-center cursor-pointer select-none hover:bg-gray-100">
                                                    +
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">
                                                {{ showAmount($item['price']) }}
                                            </span>
                                        </div>
                                        <button
                                            wire:click="removeFromCart('{{ $item['product']->id }}:{{ $item['variation']?->id }}')"
                                            class="text-red-500 hover:text-red-700">
                                            <i class="!text-xl las la-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-10 text-gray-500">
                            Your cart is empty
                        </div>
                    @endif
                </div>

                <!-- Cart Footer -->
                @if (count($cartItems) > 0)
                    <div class="px-4 py-3 border-t border-gray-200">
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-700 font-semibold">Total</span>
                            <span class="text-gray-900 font-bold">
                                {{ showAmount($this->getTotalPrice()) }}
                            </span>
                        </div>
                        <a href="{{ route('checkout') }}"
                            class="w-full bg-primary text-white py-2 rounded hover:bg-primary/90 text-center block">
                            Proceed to Checkout
                        </a>
                    </div>
                @endif
            </div>
        </nav>
    </div>
</div>
