@props(['product', 'soldProgress' => false, 'text' => ''])
<div
    class="bg-white border border-gray-300 shadow-md hover:shadow-md transition-shadow duration-300 overflow-hidden group">
    <a href="{{ route('product.details', $product->slug) }}" class="aspect-square overflow-hidden">
        <img src="{{ $product->picture_url }}" alt="{{ $product->name }}"
            class="w-full min-h-[150px] h-[150px] sm:min-h-[220px] sm:h-[220px] md:min-h-[260px] max-h-[260px] object-contain group-hover:scale-105 p-2 transition-transform duration-300">
    </a>

    <div class="p-2 md:p-4">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('product.details', $product->slug) }}">
                    <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 line-clamp-1">
                        {{ $product->name }}
                    </h3>
                </a>
            </div>
            <div>
                @livewire('favorite-toggle', ['productId' => $product->id, 'isFavorite' => !empty($product->is_favorite)], key('fav-' . $product->id . '-' . $text))
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-1 md:mb-3 line-clamp-1">
            {{ $product->category?->name ?? 'Uncategorized' }}
        </p>

        <div class="flex justify-between items-center gap-3 mb-1 md:mb-3">
            {{-- @if ($product->variations->isNotEmpty())
                <div class="text-sm md:text-xl font-bold text-gray-900">
                    {{ showAmount($product->variations?->min('price') ?? 0) }}
                </div>
                <div class="text-sm text-red-500 line-through">
                    {{ showAmount($product->price) }}
                </div>
            @else --}}
            <div class="text-sm md:text-xl font-bold text-gray-900">
                {{ showAmount($product->final_price) }}
            </div>
            @if ($product->discount > 0)
                <div class="text-sm text-red-500 line-through">
                    {{ showAmount($product->price) }}
                </div>
            @endif
            {{-- @endif --}}
        </div>
        @livewire('product-quick-view-button', ['product' => $product], key($product->id . $text))
    </div>
</div>
