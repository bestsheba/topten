@if ($product->variations->isEmpty())
    <div x-data="{ quantity: 1 }">
        <div class="mt-1.5 md:mt-4">
            <h3 class="text-sm text-gray-800 uppercase mb-1">Quantity</h3>
            <div class="flex border border-gray-300 text-gray-600 divide-x divide-gray-300 w-max">
                <div @click="quantity > 1 ? quantity-- : quantity"
                    class="h-8 w-8 text-xl flex items-center justify-center cursor-pointer select-none">
                    -
                </div>
                <div class="h-8 w-8 text-base flex items-center justify-center" x-text="quantity">
                </div>
                <div @click="quantity++"
                    class="h-8 w-8 text-xl flex items-center justify-center cursor-pointer select-none">
                    +
                </div>
            </div>
        </div>
        <div class="md:flex justify-center lg:justify-start gap-3 border-gray-200 pb-5 md:pt-5">
            <div class="mt-2 flex flex-wrap gap-3 md:justify-center lg:justify-start items-center">
                <button wire:click="$set('quantity', $refs.quantityInput.value); addToCart()" x-ref="quantityInput"
                    x-model="quantity"
                    class="py-2.5 px-4 text-center whitespace-nowrap text-white hover:text-primary border hover:border-primary rounded hover:bg-transparent bg-red-500 transition">
                    Order Now
                </button>
            </div>
        </div>
    </div>
@else
    <form wire:submit.prevent="addToCart" id="variationForm">
        @foreach ($product->attributes as $attributeName => $values)
            <div class="mb-1.5 md:mb-4">
                <label class="text-sm md:text-base block font-medium md:mb-2">{{ $attributeName }}</label>
                <div class="flex flex-wrap gap-2 attribute-options">
                    @foreach ($values as $valueId => $valueName)
                        <button type="button" wire:click="$set('variation_id', {{ $valueId }})"
                            class="px-2 md:px-4 py-1 md:py-2 border rounded-lg text-sm 
                                {{ $variation_id == $valueId ? 'bg-blue-600 text-white' : 'bg-white hover:bg-blue-100' }} 
                                transition"
                            data-attribute="{{ $attributeName }}" data-value="{{ $valueId }}">
                            {{ $valueName }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="mb-1.5 md:mb-3">
            <label for="variationQuantity" class="block mb-1.5 md:mb-2 text-sm font-medium">Quantity</label>
            <div class="flex border border-gray-300 text-gray-600 divide-x divide-gray-300 w-max">
                <div wire:click="$set('quantity', Math.max(1, quantity - 1))"
                    class="h-8 w-8 text-xl flex items-center justify-center cursor-pointer select-none hover:bg-gray-100">
                    -
                </div>
                <div class="h-8 w-8 text-base flex items-center justify-center">
                    {{ $quantity }}
                </div>
                <div wire:click="$set('quantity', quantity + 1)"
                    class="h-8 w-8 text-xl flex items-center justify-center cursor-pointer selector-none hover:bg-gray-100">
                    +
                </div>
            </div>
        </div>

        <div class="flex space-x-2">
            <button type="submit" @disabled(!$variation_id)
                class="py-2.5 px-4 text-center whitespace-nowrap text-white hover:text-primary border hover:border-primary rounded hover:bg-transparent bg-green-500 transition">
                <i class="bi bi-cart-plus"></i> Order Now
            </button>
        </div>
    </form>
@endif
