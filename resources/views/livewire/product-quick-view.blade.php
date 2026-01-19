<div x-data="{ modalOpen: @entangle('modalOpen') }" x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90" @click.outside="$wire.closeModal()"
    class="min-w-screen h-screen fixed left-0 top-0 flex justify-center items-center inset-0 z-[999999] bg-black/80 px-2 md:px-0"
    x-cloak>
    <div x-data="{ showReviewForm: @entangle('showReviewForm') }" class="w-full max-w-4xl bg-white md:rounded-lg shadow-lg relative max-h-[90vh]">
        <button @click="$wire.closeModal()"
            class="absolute -top-2 -right-2 text-white bg-red-500 hover:bg-red-600 rounded-full p-1 z-50 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="overflow-y-auto max-h-[80vh]">
            @if ($product)
                <div class="flex flex-col md:flex-row">
                    <!-- Image Slider Section -->
                    <div x-data="imageSlider({{ json_encode($images) }})" class="w-full md:w-1/2 relative p-2 md:p-4">
                        <div class="relative h-64 md:h-96 flex items-center justify-center overflow-hidden">
                            <template x-for="(image, index) in images" :key="index">
                                <img x-show="currentIndex === index" :src="image"
                                    :alt="'Product Image ' + (index + 1)"
                                    class="max-w-full max-h-full object-contain transition-opacity duration-300"
                                    x-transition:enter="opacity-0" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100">
                            </template>

                            <!-- Previous Button -->
                            <button @click="prevImage"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/50 rounded-full p-2 hover:bg-white/75 transition border">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <!-- Next Button -->
                            <button @click="nextImage"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/50 rounded-full p-2 hover:bg-white/75 transition border">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <!-- Thumbnail Indicators -->
                            <div class="absolute bottom-2 left-0 right-0 flex justify-center space-x-2">
                                <template x-for="(image, index) in images" :key="index">
                                    <button @click="currentIndex = index" class="w-2 h-2 rounded-full"
                                        :class="currentIndex === index ? 'bg-gray-700' : 'bg-gray-300'"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="w-full md:w-1/2 p-4 md:p-6 space-y-3">
                        <h1 class="text-base md:text-2xl font-semibold mb-2">
                            {{ $product->name }}
                        </h1>
                        <div class="w-full flex items-center justify-between mb-2 md:mb-4">
                            <div class="w-full flex items-center justify-between space-x-3">
                                <div class="text-xs text-gray-500">
                                    <span class="font-semibold">{{ number_format($product->total_sales ?? 0) }}</span>
                                    Sold
                                </div>
                                <div class="flex items-center space-x-1">
                                    <x-frontend.star :rating="round($product->average_rating ?? 0)" :size="'w-4 h-4'" :activeColor="'text-yellow-500'"
                                        :inactiveColor="'text-gray-300'" />
                                    <span class="text-xs text-gray-500 ml-1">
                                        ({{ number_format($product->average_rating ?? 0, 1) }})
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($product->variations->isNotEmpty())
                            <div class="flex items-center mb-2 md:mb-4 text-sm md:text-xl font-medium">
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
                            <div class="flex items-center gap-2 mb-2 md:mb-4">
                                <div class="text-sm md:text-xl font-medium">
                                    {{ showAmount($product->final_price) }}
                                </div>
                                @if ($product->discount > 0)
                                    <div class="text-sm text-red-500 line-through">
                                        {{ showAmount($product->price) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($product->variations->isNotEmpty())
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
                        </div>

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
                                <label for="variationQuantity"
                                    class="block mb-1 md:mb-2 text-xs md:text-sm font-medium">Quantity</label>
                                <div class="flex border border-gray-300 text-gray-600 divide-x divide-gray-300 w-max">
                                    <div wire:click="updateQuantity(-1)"
                                        class="h-6 md:h-8 w-6 md:w-8 text-base md:text-xl flex items-center justify-center cursor-pointer select-none hover:bg-gray-100">
                                        -
                                    </div>
                                    <div
                                        class="h-6 md:h-8 w-6 md:w-8 text-xs md:text-base flex items-center justify-center">
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

                        <div class="flex space-x-2">
                            <button wire:click="orderNow"
                                class="w-full bg-primary text-white py-2 rounded hover:bg-primary/50 transition text-sm md:text-base 
                            {{ $this->isProductInCart() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $this->isProductInCart() ? 'disabled' : '' }}>
                                {{ $this->isProductInCart() ? 'Added' : 'Order Now' }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-6">
                    @if ($product->description)
                        <div class="w-full prose text-gray-700 my-1 md:my-4 mt-5" style="width:100%;">
                            <h2 class="text-lg md:text-xl font-bold mb-0 pb-0">
                                Description
                            </h2>
                            {!! $product->description ?? '' !!}
                        </div>
                    @endif

                    <!-- Reviews Section -->
                    <div class="mt-8 bg-white shadow rounded-lg p-6 border">
                        <h2 class="text-lg lg:text-2xl font-bold mb-6 border-b pb-3">Customer Reviews</h2>

                        {{-- Overall Rating Summary --}}
                        <div class="grid md:grid-cols-3 gap-6 border-b pb-6 mb-6">
                            {{-- Average Rating --}}
                            <div class="text-center">
                                <h3 class="text-md lg:text-xl font-semibold mb-4">Average Rating</h3>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-7 h-7 lg:w-10 lg:h-10 {{ $i <= round($averageRating) ? 'text-yellow-500' : 'text-gray-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl lg:text-4xl font-bold text-gray-800">
                                            {{ number_format($averageRating, 1) }}</p>
                                        <p class="text-gray-600">out of 5.0</p>
                                        <p class="text-sm text-gray-500">
                                            @if ($totalRatings > 0)
                                                ({{ number_format($totalRatings) }} reviews)
                                            @else
                                                <span class="text-gray-400">No reviews yet</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Rating Breakdown --}}
                            <div>
                                <h3 class="text-md lg:text-xl font-semibold mb-4">Rating Breakdown</h3>
                                @php
                                    $ratingCounts = $product
                                        ->reviews()
                                        ->selectRaw('star, COUNT(*) as count')
                                        ->groupBy('star')
                                        ->orderBy('star', 'desc')
                                        ->pluck('count', 'star')
                                        ->toArray();
                                    // Use totalRatings passed from component or count from collection
                                    $totalReviewsCount = $totalRatings;
                                @endphp
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center mb-2">
                                        <div class="w-16 text-right mr-2">
                                            {{ $i }} Star
                                        </div>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-yellow-500 h-2.5 rounded-full"
                                                style="width: {{ $totalReviewsCount > 0 && isset($ratingCounts[$i]) ? ($ratingCounts[$i] / $totalReviewsCount) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <div class="w-10 text-left text-gray-600">
                                            {{ isset($ratingCounts[$i]) ? $ratingCounts[$i] : 0 }}
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            {{-- Review Submission CTA --}}
                            <div class="flex flex-col justify-center items-center md:border-l pl-6">
                                <p class="text-lg text-gray-700 mb-4 text-center">Share your experience!</p>
                                @auth
                                    <button type="button" @click="showReviewForm = !showReviewForm"
                                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        Write a Review
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                        Login to Review
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Review Submission Form --}}
                        @auth
                            <div id="reviewFormContainer" x-show="showReviewForm" x-cloak>
                                <h3 class="text-md lg:text-xl font-semibold mb-4 border-b pb-3">Write Your Review</h3>

                                @if (session()->has('message'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                                        role="alert">
                                        <span class="block sm:inline">{{ session('message') }}</span>
                                    </div>
                                @endif

                                <form wire:submit.prevent="submitReview">
                                    {{-- Star Rating Input --}}
                                    <div x-data="{ rating: @entangle('rating') }">
                                        <label class="block text-gray-700 font-bold mb-2">Your Rating</label>
                                        <div class="flex items-center space-x-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="rating = {{ $i }}"
                                                    class="focus:outline-none">
                                                    <svg class="w-10 h-10 transition-colors duration-200"
                                                        :class="rating >= {{ $i }} ? 'text-yellow-500' :
                                                            'text-gray-300 hover:text-yellow-400'"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Review Text Input --}}
                                    <div>
                                        <label for="comment" class="block text-gray-700 font-bold mb-2">Your
                                            Review</label>
                                        <textarea wire:model="comment" rows="4"
                                            class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="Share your detailed experience with this product" required></textarea>
                                        @error('comment')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="mt-4">
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                            Submit Review
                                        </button>
                                    </div>
                                    <br>
                                </form>
                            </div>
                        @endauth

                        {{-- Reviews List --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-3">
                                <h3 class="text-lg lg:text-2xl font-bold text-gray-800">Customer Reviews
                                    <span class="text-sm text-gray-500 ml-2">({{ $totalRatings }})</span>
                                </h3>
                            </div>

                            @forelse ($reviews as $review)
                                <div
                                    class="bg-white shadow-sm rounded-lg p-5 border border-gray-100 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-bold uppercase">
                                                    {{ substr($review->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-base font-semibold text-gray-800">
                                                    {{ $review->user->name }}
                                                </h4>
                                                <div class="flex items-center text-yellow-500 space-x-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->star ? 'text-yellow-500' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            @empty
                                <div
                                    class="text-center bg-gray-50 rounded-lg p-2 lg:p-10 border border-dashed border-gray-200">
                                    <h3 class="mt-2 lg:mt-4 text-md lg:text-xl font-semibold text-gray-700">No Reviews
                                        Yet
                                    </h3>
                                    <p class="mt-2 text-gray-500">Be the first to share your experience with this
                                        product!
                                    </p>
                                </div>
                            @endforelse

                            {{-- Pagination --}}
                            @if ($reviews->total() > 0)
                                <div class="mt-6 flex justify-center">
                                    {{ $reviews->links('pagination::tailwind') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Products Section --}}
                    @if ($related_products->count() > 0)
                        <section class="mt-2 bg-white shadow rounded-lg p-6 border">
                            <h2 class="text-lg lg:text-2xl font-bold mb-6 border-b pb-3">Related Products</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($related_products as $related_product)
                                    <x-frontend.product-card :product="$related_product" />
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
                <script>
                    function updateStars(input) {
                        const container = input.closest('.star-rating-input');
                        const value = parseInt(input.value);
                        const stars = container.querySelectorAll('svg');

                        stars.forEach((star, index) => {
                            if (index < value) {
                                star.classList.remove('text-gray-300');
                                star.classList.add('text-yellow-500');
                            } else {
                                star.classList.remove('text-yellow-500');
                                star.classList.add('text-gray-300');
                            }
                        });
                    }
                </script>
            @endif
        </div>
    </div>
</div>

<script>
    function imageSlider(images = []) {
        return {
            images: images,
            currentIndex: 0,
            nextImage() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            },
            prevImage() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        }
    }
</script>
