<div class="w-full space-y-3 mt-1">
    <h1 class="text-base md:text-2xl font-semibold mb-2">
        {{ $product->name }}
    </h1>
    {{-- <div class="w-full flex items-center justify-between mb-2 md:mb-4">
        <div class="w-full flex items-center justify-between space-x-3">
            <div class="text-xs text-gray-500">
                <span class="font-semibold">{{ number_format($product->total_sales ?? 0) }}</span> Sold
            </div>
            <div class="flex items-center space-x-1">
                <x-frontend.star :rating="round($product->average_rating ?? 0)" :size="'w-4 h-4'" :activeColor="'text-yellow-500'" :inactiveColor="'text-gray-300'" />
                <span class="text-xs text-gray-500 ml-1">
                    ({{ number_format($product->average_rating ?? 0, 1) }})
                </span>
            </div>
        </div>
    </div> --}}

    @if ($product->variations->isNotEmpty())
        <div class="flex items-center mb-2 md:mb-4 text-xl md:text-xl font-medium">
            <span id="variationPrice">
                {{ showAmount($product_variations_min_price) }}
            </span>
            {{-- @if ($product_variations_min_price != $product_variations_max_price)
                <span class="text-gray-500 ml-2">
                    - <span class="line-through">{{ showAmount($product_variations_max_price) }}</span>
                </span>
            @endif --}}
            @if ($product->discount > 0 && $product_variations_min_price < $product->price)
                <div class="text-sm md:text-sm font-medium ml-3 text-red-500 line-through">
                    {{ showAmount($product->price) }}
                </div>
            @endif
        </div>
    @else
        @if ($product->discount > 0)
            <div class="flex items-center gap-4">
                <div class="text-xl md:text-xl font-medium mb-2 md:mb-4">
                    {{ showAmount($product->final_price) }}
                </div>
                <div class="text-sm md:text-sm font-medium mb-2 md:mb-4 text-red-500 line-through">
                    {{ showAmount($product->price) }}
                </div>
            </div>
        @else
            <div class="text-xl md:text-xl font-medium mb-2 md:mb-4">
                {{ showAmount($product->price) }}
            </div>
        @endif
    @endif
    {{-- @if ($product->variations->isNotEmpty())
        <div class="text-xs md:text-sm font-medium mb-2 md:mb-3">
            @if ($product->variations->sum('stock') > 0)
                <span class="text-green-600">
                    <i class="bi bi-check-circle"></i> In Stock
                    ({{ $product->variations->sum('stock') }} available)
                </span>
            @else
                <span class="text-red-600">
                    <i class="bi bi-x-circle"></i> Out of Stock
                </span>
            @endif
        </div>
    @endif

    <div class="space-y-1 md:space-y-2 mb-2 md:mb-4">
        <p class="space-x-2 text-xs md:text-base flex flex-wrap items-center">
            <span class="text-gray-800 font-semibold">
                Category: <span class="font-normal">{{ $product?->category?->name ?? 'N/A' }}</span>
            </span>
            <span class="text-gray-800 font-semibold">
                <span class="mr-1">|</span> Brand: <span
                    class="font-normal">{{ $product?->brand?->name ?? 'N/A' }}</span>
            </span>
        </p>
        <p class="text-xs md:text-base">
            <span class="text-gray-800 font-semibold">
                SKU: <span class="font-normal">{{ $product->sku ?? 'N/A' }}</span>
            </span>
        </p>
    </div> --}}

    <!-- Dynamic Attribute Selection -->
    @foreach ($productAttributes as $attributeName => $values)
        <div class="mb-2 md:mb-4">
            <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-2">{{ $attributeName }}</p>
            <div class="flex space-x-2 flex-wrap gap-2">
                @foreach ($values as $value)
                    <button wire:click="selectVariation({{ $value['variation'] }})"
                        class="border rounded py-1 px-2 text-xs md:text-sm 
                        {{ $selectedVariation && $selectedVariation->attributeValues->contains('id', $value['id']) ? 'bg-blue-600 text-white' : 'bg-white' }}">
                        {{ $value['value'] }}
                    </button>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mb-2 md:mb-4">
        <div class="mb-1 md:mb-3">
            <label for="variationQuantity" class="block mb-1 md:mb-2 text-xs md:text-sm font-medium">Quantity</label>
            <div class="flex border border-gray-300 text-gray-600 divide-x divide-gray-300 w-max">
                <div wire:click="updateQuantity(-1)"
                    class="h-6 md:h-8 w-6 md:w-8 text-base md:text-xl flex items-center justify-center cursor-pointer select-none hover:bg-gray-100">
                    -
                </div>
                <div class="h-6 md:h-8 w-6 md:w-8 text-xs md:text-base flex items-center justify-center">
                    {{ $quantity }}
                </div>
                <div wire:click="updateQuantity(1)"
                    class="h-6 md:h-8 w-6 md:w-8 text-base md:text-xl flex items-center justify-center cursor-pointer selector-none hover:bg-gray-100">
                    +
                </div>
            </div>
            <input type="hidden" wire:model="quantity" min="1">
        </div>
    </div>

    <div class="w-full flex items-center p-0 m-0" style="gap:0;">
        <button wire:click="addToCartAction"
            class="w-full md:w-auto rounded rounded-r-none py-2.5 px-4 text-center text-text-primary bg-primary hover:bg-secondary transition  
            {{ $this->isProductInCart() ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ $this->isProductInCart() ? 'disabled' : '' }}>
            {{ $this->isProductInCart() ? '  Added  ' : 'Add to Cart' }}
        </button>
        <button wire:click="orderNow" type="button"
            class="w-full md:w-auto rounded rounded-l-none py-2.5 px-4 text-center text-white bg-green-500 transition">
            Order Now
        </button>
    </div>
</div>
