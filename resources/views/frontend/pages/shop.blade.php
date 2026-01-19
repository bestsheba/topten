@extends('frontend.layouts.front')
@section('title', 'Product')
@section('content')
    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">Shop</p>
    </div>
    <!-- ./breadcrumb -->
    <!-- shop wrapper -->
    <div class="container grid md:grid-cols-4 grid-cols-2 gap-6 pt-4 pb-16 items-start" x-data="{ filterDrawer: false }">
        <!-- sidebar -->

        <!-- drawer init and toggle -->
        <div class="text-center md:hidden flex items-center gap-2">
            <button @click="filterDrawer = !filterDrawer"
                class="whitespace-nowrap text-white bg-primary hover:bg-primary-400 focus:ring-1 focus:ring-primary-400 font-medium rounded text-sm px-5 py-2 mr-2 mb-2 focus:outline-none block md:hidden">
                Filter
            </button>
            @if (request('categories') || request('brands') || request('min_price') || request('max_price'))
                <a href="{{ route('shop') }}"
                    class="whitespace-nowrap text-black hover:text-white border border-primary-500 hover:bg-primary-400 focus:ring-1 focus:ring-primary-400 font-medium rounded text-sm px-5 py-2 mr-2 mb-2 focus:outline-none">
                    Clear Filter
                </a>
            @endif
        </div>

        <!-- drawer component -->
        <div x-show="filterDrawer"
            class="fixed !top-[11rem] h-screen left-0 z-40 p-4 overflow-y-auto bg-white w-80 border-r-2 border-primary"
            @click.away="filterDrawer = false" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full" x-cloak>
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-primary">
                Filter
            </h5>
            <button type="button" @click="filterDrawer = !filterDrawer"
                class=" hover:bg-primary-400 bg-primary rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center text-white">
                <i class="text-md las la-times text-white"></i>
                <span class="sr-only">
                    Close menu
                </span>
            </button>
            <div class="divide-y divide-gray-200 space-y-5">
                <x-frontend.shop.filter-sidebar :categories="$categories" :brands="$brands" :offerProductCount="$offer_product_count" />
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>

        <!-- ./sidebar -->
        <div class="col-span-1 bg-white px-4 pb-6 border rounded overflow-hiddenb hidden md:block">
            <div class="divide-y divide-gray-200 space-y-5">
                <x-frontend.shop.filter-sidebar :categories="$categories" :brands="$brands" :offerProductCount="$offer_product_count" />
            </div>
        </div>

        <!-- products -->
        <div class="col-span-3">
            <div class="flex items-center mb-4">
                <div class="flex items-center gap-2">
                    <select name="sort" id="sort"
                        class="w-44 px-5 py-2 text-sm text-gray-600 border-gray-300 shadow-sm rounded focus:ring-primary focus:border-primary"
                        onchange="handleSortChange()">
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Default sorting</option>
                        <option value="1" {{ request('sort') == '1' ? 'selected' : '' }}>Price low to high</option>
                        <option value="2" {{ request('sort') == '2' ? 'selected' : '' }}>Price high to low</option>
                        <option value="3" {{ request('sort') == '3' ? 'selected' : '' }}>Latest product</option>
                    </select>

                    @if (request('categories') || request('brands') || request('min_price') || request('max_price') || request('sort'))
                        <a href="{{ route('shop') }}"
                            class="hidden md:block whitespace-nowrap text-black hover:text-white border border-primary-500 hover:bg-primary-400 focus:ring-1 focus:ring-primary-400 font-medium rounded text-sm px-5 py-2 mr-2 focus:outline-none">
                            Clear Filter
                        </a>
                    @endif
                </div>
                {{-- <div class="flex gap-2 ml-auto">
                    <div
                        class="border border-primary w-10 h-9 flex items-center justify-center text-white bg-primary rounded cursor-pointer">
                        <i class="fa-solid fa-grip-vertical"></i>
                    </div>
                    <div
                        class="border border-gray-300 w-10 h-9 flex items-center justify-center text-gray-600 rounded cursor-pointer">
                        <i class="fa-solid fa-list"></i>
                    </div>
                </div> --}}
            </div>
            <div class="grid md:grid-cols-4 grid-cols-2 gap-1">
                @forelse ($products as $product)
                    <x-frontend.product-card :product="$product" />
                @empty
                    <div class="text-center text-gray-400 col-span-full mt-8">
                        No product found...
                    </div>
                @endforelse
            </div>
            <div class="col-span-full mt-8">
                {{ $products->links('pagination::tailwind') }}
            </div>
        </div>
        <!-- ./products -->
    </div>
    <!-- ./shop wrapper -->
@endsection
@push('scripts')
    <script>
        function handleSortChange() {
            const sortOption = document.getElementById('sort').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortOption);
            window.location.href = url.toString();
        }
    </script>
@endpush
