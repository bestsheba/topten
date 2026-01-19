@foreach ($header_categories as $key => $header_category)
    <div class="mobile-category-item">
        <a class="flex items-center justify-between p-3 text-gray-700 hover:bg-primary/5 hover:text-primary rounded-lg transition duration-300 mb-1"
            href="{{ route('shop', ['categories' => [$header_category->id]]) }}">
            <div class="flex items-center">
                <div class="category-icon-mobile mr-3">
                    <i
                        class="las {{ ['la-tag', 'la-tshirt', 'la-gem', 'la-mobile', 'la-headphones', 'la-home', 'la-laptop', 'la-heart'][$key % 8] }}"></i>
                </div>
                <div>
                    <span class="font-medium">{{ $header_category->name }}</span>
                    @if ($key % 5 == 1)
                        <span class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-md">HOT</span>
                    @endif
                </div>
            </div>
            <i class="las la-angle-right text-primary/70"></i>
        </a>
    </div>
@endforeach
