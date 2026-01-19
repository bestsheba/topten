@props(['categories', 'brands', 'offerProductCount'])
<form action="{{ route('shop') }}" class="pt-4" id="filter-form">
    @if ($categories->count() > 0)
        <div>
            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">
                Categories
            </h3>
            <div class="space-y-2">
                @foreach ($categories as $category)
                    <div class="grid divide-y divide-neutral-200 mx-auto border border-gray-100 p-1">
                        <details class="group"
                            {{ count(array_intersect($category->subCategories()->pluck('id')->toArray(), request('sub_categories', []))) > 0 ? 'open' : '' }}>
                            <summary
                                class="flex justify-between items-center font-medium cursor-pointer list-none px-1 py-2">
                                <span>
                                    {{ $category->name }}
                                </span>
                                <span class="transition group-open:rotate-180">
                                    <i class="las la-angle-down ml-2 h-4 w-4"></i>
                                </span>
                            </summary>
                            @foreach ($category->subCategories as $sub_category)
                                <label class="flex items-center px-4 py-1">
                                    <input type="checkbox" name="sub_categories[]" value="{{ $sub_category->id }}"
                                        id="cat-{{ $sub_category->id }}"
                                        class="text-primary focus:ring-0 rounded-sm cursor-pointer"
                                        @checked(in_array($sub_category->id, request('sub_categories', [])))
                                        onchange="this.form.submit()">
                                    <div class="text-gray-600 ml-3 cusror-pointer">
                                        {{ $sub_category->name }}
                                    </div>
                                    <div class="ml-auto text-gray-600 text-sm">
                                        ({{ $sub_category->products_count }})
                                    </div>
                                </label>
                            @endforeach
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="pt-4">
        <div class="space-y-2">
            <label class="flex items-center">
                <input type="checkbox" name="offer" value="1" id="offer"
                    class="text-primary focus:ring-0 rounded-sm cursor-pointer" 
                    @checked(request('offer') == 1)
                    onchange="this.form.submit()">
                <div class="text-gray-600 ml-3 cusror-pointer">
                    Offer
                </div>
                <div class="ml-auto text-gray-600 text-sm">
                    ({{ $offerProductCount }})
                </div>
            </label>
        </div>
    </div>
    @if ($brands->count() > 0)
        <div class="pt-4">
            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">
                Brands
            </h3>
            <div class="space-y-2">
                @foreach ($brands as $brand)
                    <label class="flex items-center">
                        <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                            id="brand-{{ $brand->id }}" 
                            class="text-primary focus:ring-0 rounded-sm cursor-pointer"
                            @checked(in_array($brand->id, request('brands', [])))
                            onchange="this.form.submit()">
                        <div class="text-gray-600 ml-3 cusror-pointer">
                            {{ $brand->name }}
                        </div>
                        <div class="ml-auto text-gray-600 text-sm">
                            ({{ $brand->products_count }})
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endif
    <div class="pt-4">
        <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">
            Price
        </h3>
        <div class="mt-4 flex items-center">
            <input type="number" step="any" name="min_price" id="min"
                class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                placeholder="min" value="{{ request('min_price') }}">
            <span class="mx-3 text-gray-500">-</span>
            <input type="number" step="any" name="max_price" id="max"
                class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                placeholder="max" value="{{ request('max_price') }}">
            <script>
                document.getElementById('min').addEventListener('change', function() {
                    this.form.submit();
                });
                document.getElementById('max').addEventListener('change', function() {
                    this.form.submit();
                });
            </script>
        </div>
    </div>
    <button type="submit"
        class="mt-4 !w-full items-center px-4 py-2 text-sm font-medium text-center text-text-primary bg-primary rounded hover:bg-secondary focus:ring-4 focus:ring-primary focus:outline-none">
        Apply
    </button>
</form>
