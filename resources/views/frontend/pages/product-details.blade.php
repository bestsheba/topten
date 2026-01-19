@extends('frontend.layouts.app')
@section('title', $product->meta_title ?: $product->name . ' | ' . ($settings?->website_name ?: 'Product'))
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->description), 160, '...'))
@section('meta_keywords', $product->meta_keywords ?: implode(', ', array_filter([
    $product->category?->name, 
    $product->brand?->name, 
    'product'
])))
@section('meta_image', $product->meta_image ? asset('storage/' . $product->meta_image) : asset('/' . $product->picture))
@section('additional_meta_tags')
    @if($product->brand)
        <meta name="author" content="{{ $product->brand->name }}">
    @endif
@endsection
@section('content')
    <div class="container mx-auto py-10 pt-0 md:pt-10 px-4">
        <div class="flex flex-col lg:flex-row gap-1 md:gap-8">
            <div class="w-full lg:w-1/2">
                {{-- Main Image --}}
                <div class="border rounded flex items-center justify-center">
                    <img src="{{ asset('/' . $product->picture) }}" alt="{{ $product->name }}"
                        class="min-h-[400px] max-h-[400px] w-full object-contain rounded-lg transition-all duration-300 ease-out"
                        id="mainProductImage">
                </div>
                <div class="w-full flex items-center justify-start gap-2 overflow-y-auto mt-3">
                    @foreach ($product->variations as $variation)
                        @if ($variation->image)
                            <img src="{{ asset('storage/' . $variation->image) }}" alt="Variation {{ $loop->iteration }}"
                                class="min-h-[40px] max-h-[60px] max-w-[60px] md:min-h-[60px] md:max-h-[80px] md:max-w-[80px] object-cover border rounded cursor-pointer variation-thumbnail hover:opacity-80 hover:border-blue-600"
                                data-variation="{{ $variation->id }}"
                                data-image="{{ asset('storage/' . $variation->image) }}">
                        @endif
                    @endforeach
                    @foreach ($product->galleries as $key => $gallery)
                        @if ($gallery->picture_url)
                            <img src="{{ $gallery->picture_url }}" alt="Variation {{ $loop->iteration }}"
                                class="min-h-[40px] max-h-[60px] max-w-[60px] md:min-h-[60px] md:max-h-[80px] md:max-w-[80px] object-cover border rounded cursor-pointer variation-thumbnail hover:opacity-80 hover:border-blue-600"
                                data-variation="{{ $key }}" data-image="{{ $gallery->picture_url }}">
                        @endif
                    @endforeach
                </div>
                {{-- <div class="text-gray-700 mb-6">{!! $product->description ?? '' !!}</div> --}}
            </div>
            <!-- Product Details -->
            <div class="w-full lg:w-1/2">
                @livewire('add-to-cart', ['product' => $product], key($product->id))
                @if (!empty($product->video))
                    <div class="bg-white">
                        <button id="openModalBtn"
                            class="bg-blue-600 w-full md:w-auto text-white px-4 py-2 rounded hover:bg-blue-700 mt-4 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            aria-haspopup="dialog" aria-controls="videoModal">
                            Watch Product Video
                        </button>
                        <div id="videoModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto"
                            role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                            <!-- Modal Container -->
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <!-- Modal Content - Fixed 600x600 size -->
                                <div
                                    class="relative bg-white rounded-lg shadow-xl w-[600px] h-[600px] flex flex-col overflow-hidden">
                                    <!-- Modal Header -->
                                    <div class="flex justify-between items-center p-4 border-b">
                                        <h2 id="modalTitle" class="text-xl font-semibold">Product Video</h2>
                                        <button id="closeModalBtn"
                                            class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1"
                                            aria-label="Close modal">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-1 bg-black">
                                        <video id="modalVideo" controls class="w-full h-full object-contain"
                                            aria-label="Product demonstration video">
                                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <!-- Modal Footer (optional) -->
                                    <div class="p-4 border-t text-right">
                                        <button id="closeModalBtn2"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4 bg-white rounded-md overflow-hidden border">
                    @if (!empty($product->size_guide))
                        <button id="openSizeGuideBtn" type="button" class="w-full text-left flex items-center justify-between px-4 py-3 border-b hover:bg-gray-50 cursor-pointer" onclick="return false;">
                            <span class="flex items-center gap-2 text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16v16H4z"/>
                                    <path d="M8 8h8M8 12h6"/>
                                </svg>
                                Size Guide
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Size Guide Modal -->
                        <div id="sizeGuideModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="sizeGuideTitle">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="relative bg-white rounded-lg shadow-xl w-[600px] max-w-full max-h-[90vh] flex flex-col">
                                    <div class="flex justify-between items-center p-4 border-b flex-none">
                                        <h2 id="sizeGuideTitle" class="text-xl font-semibold">Size Guide</h2>
                                        <button id="closeSizeGuideBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1" aria-label="Close modal">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-4 overflow-y-auto flex-1">
                                        {!! $product->size_guide !!}
                                    </div>
                                    <div class="p-4 border-t text-right flex-none">
                                        <button id="closeSizeGuideBtn2" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a id="openDeliveryReturnBtn" href="{{ route('custom.page', 'delivery-return') }}" class="flex items-center justify-between px-4 py-3 border-b hover:bg-gray-50">
                        <span class="flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h13v10H3z"/>
                                <path d="M16 10h4l1 2v5h-5z"/>
                                <circle cx="7.5" cy="17" r="1.5"/>
                                <circle cx="18.5" cy="17" r="1.5"/>
                            </svg>
                            Delivery & Return
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a id="openAskQuestionBtn" href="{{ route('custom.page', 'ask-a-question') }}" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50">
                        <span class="flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4"/>
                                <path d="M9 9h6"/>
                                <path d="M9 13h3"/>
                            </svg>
                            Ask a Question
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <!-- Delivery & Return Modal -->
                <div id="deliveryReturnModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="deliveryReturnTitle">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-white rounded-lg shadow-xl w-[600px] max-w-full max-h-[90vh] flex flex-col">
                            <div class="flex justify-between items-center p-4 border-b flex-none">
                                <h2 id="deliveryReturnTitle" class="text-xl font-semibold">Delivery & Return</h2>
                                <button id="closeDeliveryReturnBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1" aria-label="Close modal">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div id="deliveryReturnContent" class="p-4 overflow-y-auto flex-1"></div>
                            <div class="p-4 border-t text-right flex-none">
                                <button id="closeDeliveryReturnBtn2" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ask a Question Modal -->
                <div id="askQuestionModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="askQuestionTitle">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-white rounded-lg shadow-xl w-[600px] max-w-full max-h-[90vh] flex flex-col">
                            <div class="flex justify-between items-center p-4 border-b flex-none">
                                <h2 id="askQuestionTitle" class="text-xl font-semibold">Ask a Question</h2>
                                <button id="closeAskQuestionBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1" aria-label="Close modal">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div id="askQuestionContent" class="p-4 overflow-y-auto flex-1"></div>
                            <div class="p-4 border-t text-right flex-none">
                                <button id="closeAskQuestionBtn2" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($product->description)
            <div class="w-full prose text-gray-700 my-1 md:my-4 mt-5" style="width:100%;">
                <h2 class="text-lg md:text-xl font-bold mb-0 pb-0">
                    Description
                </h2>
                {!! $product->description ?? '' !!}
            </div>
        @endif

        <!-- Reviews Section -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
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
                            <p class="text-xl lg:text-4xl font-bold text-gray-800">{{ number_format($averageRating, 1) }}</p>
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
                        $totalReviews = $reviews->total();
                    @endphp
                    @for ($i = 5; $i >= 1; $i--)
                        <div class="flex items-center mb-2">
                            <div class="w-16 text-right mr-2">
                                {{ $i }} Star
                            </div>
                            <div class="flex-1 bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="bg-yellow-500 h-2.5 rounded-full"
                                    style="width: {{ isset($ratingCounts[$i]) ? ($ratingCounts[$i] / $totalReviews) * 100 : 0 }}%">
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
                        <button id="writeReviewBtn"
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
                <div id="reviewFormContainer" class="hidden">
                    <h3 class="text-md lg:text-xl font-semibold mb-4 border-b pb-3">Write Your Review</h3>
                    <form action="{{ route('review.submit') }}" method="POST" class="">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- Star Rating Input --}}
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Your Rating</label>
                            <div id="starRatingInput" class="flex items-center space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="star" value="{{ $i }}" class="hidden"
                                            required>
                                        <svg class="star-icon w-10 h-10 transition-colors duration-200 {{ old('star') == $i ? 'text-yellow-500' : 'text-gray-300 hover:text-yellow-400' }}"
                                            data-value="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            @error('star')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Review Text Input --}}
                        <div>
                            <label for="comment" class="block text-gray-700 font-bold mb-2">Your Review</label>
                            <textarea name="comment" id="comment" rows="4"
                                class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                                placeholder="Share your detailed experience with this product" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div>
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
                        <span class="text-sm text-gray-500 ml-2">({{ $reviews->total() }})</span>
                    </h3>
                </div>

                @forelse ($reviews as $review)
                    <div
                        class="bg-white shadow-sm rounded-lg p-5 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center space-x-3">
                                {{-- User Avatar Placeholder --}}
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
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
                    <div class="text-center bg-gray-50 rounded-lg p-2 lg:p-10 border border-dashed border-gray-200">
                        <svg class="mx-auto h-8 w-8 lg:h-16 lg:w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 lg:mt-4 text-md lg:text-xl font-semibold text-gray-700">No Reviews Yet</h3>
                        <p class="mt-2 text-gray-500">Be the first to share your experience with this product!</p>
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
    </div>

    {{-- Related Products Section --}}
    @if ($related_products->count() > 0)
        <section class="mt-2 bg-white shadow rounded-lg p-6">
            <h2 class="text-lg lg:text-2xl font-bold mb-6 border-b pb-3">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($related_products as $related_product)
                    <x-frontend.product-card :product="$related_product" />
                @endforeach
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        @media (width <=64rem) {
            #footerLast {
                margin-bottom: 118px !important;
            }
        }

        /* Active thumbnail state */
        .variation-thumbnail.active {
            filter: blur(1.5px);
            border: 1px solid blue;
        }

        /* Blur + scale animation for main image */
        .blur-in {
            filter: blur(6px);
            transform: scale(1.02);
        }

        .animate-in {
            animation: imageReveal 320ms ease-out forwards;
        }

        @keyframes imageReveal {
            0% {
                filter: blur(6px);
                transform: scale(1.02);
                opacity: 0.7;
            }

            100% {
                filter: blur(0);
                transform: scale(1);
                opacity: 1;
            }
        }

        .table {
            width: 100% !important;
        }

        table {
            width: 100% !important;
        }

        .prose {
            max-width: 100%;
        }

        .prose :where(figure):not(:where([class~="not-prose"], [class~="not-prose"] *)) {
            margin-top: 0em;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('videoModal');
            const openBtn = document.getElementById('openModalBtn');
            const closeBtns = document.querySelectorAll('#closeModalBtn, #closeModalBtn2');
            const video = document.getElementById('modalVideo');

            // Open modal
            openBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                video.play();
            });

            // Close modal
            closeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    video.pause();
                });
            });

            // Close when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    video.pause();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    video.pause();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizeModal = document.getElementById('sizeGuideModal');
            const openSizeBtn = document.getElementById('openSizeGuideBtn');
            const closeSizeBtns = document.querySelectorAll('#closeSizeGuideBtn, #closeSizeGuideBtn2');
            
            if (openSizeBtn && sizeModal) {
                console.log('Size Guide button and modal found');
                openSizeBtn.addEventListener('click', function(e) {
                    console.log('Size Guide button clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    sizeModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
                
                closeSizeBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        sizeModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                });
                
                sizeModal.addEventListener('click', function(e) {
                    if (e.target === sizeModal) {
                        sizeModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !sizeModal.classList.contains('hidden')) {
                        sizeModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle thumbnail image clicks
            document.querySelectorAll('.variation-thumbnail').forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const mainImg = document.getElementById('mainProductImage');

                    // Set active style on selected thumbnail
                    document.querySelectorAll('.variation-thumbnail').forEach(t => t.classList
                        .remove('active'));
                    this.classList.add('active');

                    // Apply blur/animation, swap src, then animate to clear
                    mainImg.classList.add('blur-in');
                    // Swap the image source
                    mainImg.src = this.dataset.image;
                    // When new image loads, play reveal animation
                    mainImg.onload = () => {
                        mainImg.classList.remove('blur-in');
                        // retrigger animation by toggling class
                        mainImg.classList.remove('animate-in');
                        // Force reflow
                        void mainImg.offsetWidth;
                        mainImg.classList.add('animate-in');
                    };
                });
            });
            if (document.getElementById('variationForm')) {
                const variationData = Array.from(document.querySelectorAll('#variationData div'));
                const addToCartBtn = document.getElementById('addToCartBtn');
                const buyNowBtn = document.getElementById('buyNowBtn');
                const priceDisplay = document.getElementById('variationPrice');
                const selectedAttributes = {};
                // Initialize selected attributes object
                document.querySelectorAll('.attribute-options').forEach(optionGroup => {
                    const attributeName = optionGroup.querySelector('.attribute-option').dataset.attribute;
                    selectedAttributes[attributeName] = null;
                });

                // Add click handlers to attribute options
                document.querySelectorAll('.attribute-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const attributeName = this.dataset.attribute;
                        const valueId = this.dataset.value;

                        // Update selected attributes
                        selectedAttributes[attributeName] = valueId;

                        // Update UI
                        this.classList.remove('bg-white');
                        this.classList.add('bg-blue-600', 'text-white');
                        this.parentNode.querySelectorAll('.attribute-option').forEach(btn => {
                            if (btn !== this) {
                                btn.classList.remove('bg-blue-600', 'text-white');
                                btn.classList.add('bg-white');
                            }
                        });

                        checkVariationMatch();
                    });
                });

                function checkVariationMatch() {
                    const allSelected = Object.values(selectedAttributes).every(val => val !== null);
                    if (!allSelected) {
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                        return;
                    }
                    const selectedValues = Object.values(selectedAttributes);
                    const matchedVariation = variationData.find(variation => {
                        const variationAttributes = variation.dataset.attributes
                            .split(',')
                            .map(attr => attr.trim())
                            .filter(Boolean);
                        return selectedValues.every(value => variationAttributes.includes(value));
                    });
                    if (matchedVariation) {
                        // Enable both buttons
                        addToCartBtn.disabled = false;
                        buyNowBtn.disabled = false;
                        // Update price and image
                        priceDisplay.textContent = '{{ currencySymbol() }}' + parseFloat(matchedVariation.dataset
                            .price).toFixed(2);
                        document.getElementById('selectedVariationId').value = matchedVariation.dataset.variation;

                        const variationImage = document.querySelector(
                            `.variation-thumbnail[data-variation="${matchedVariation.dataset.variation}"]`
                        );
                        if (variationImage) {
                            document.getElementById('mainProductImage').src = variationImage.dataset.image;
                        }
                    } else {
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                    }
                }
            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delivery & Return modal logic
            const deliveryModal = document.getElementById('deliveryReturnModal');
            const openDeliveryBtn = document.getElementById('openDeliveryReturnBtn');
            const closeDeliveryBtns = document.querySelectorAll('#closeDeliveryReturnBtn, #closeDeliveryReturnBtn2');
            const deliveryContent = document.getElementById('deliveryReturnContent');

            if (openDeliveryBtn && deliveryModal) {
                openDeliveryBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    try {
                        deliveryContent.innerHTML = '<div class="text-gray-500">Loading...</div>';
                        const response = await fetch(openDeliveryBtn.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const html = await response.text();
                        deliveryContent.innerHTML = html;
                    } catch (err) {
                        deliveryContent.innerHTML = '<div class="text-red-500">Failed to load content.</div>';
                    }
                    deliveryModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
                closeDeliveryBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        deliveryModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                });
                deliveryModal.addEventListener('click', function(e) {
                    if (e.target === deliveryModal) {
                        deliveryModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Ask a Question modal logic
            const askModal = document.getElementById('askQuestionModal');
            const openAskBtn = document.getElementById('openAskQuestionBtn');
            const closeAskBtns = document.querySelectorAll('#closeAskQuestionBtn, #closeAskQuestionBtn2');
            const askContent = document.getElementById('askQuestionContent');

            if (openAskBtn && askModal) {
                openAskBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    try {
                        askContent.innerHTML = '<div class="text-gray-500">Loading...</div>';
                        const response = await fetch(openAskBtn.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const html = await response.text();
                        askContent.innerHTML = html;
                    } catch (err) {
                        askContent.innerHTML = '<div class="text-red-500">Failed to load content.</div>';
                    }
                    askModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
                closeAskBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        askModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                });
                askModal.addEventListener('click', function(e) {
                    if (e.target === askModal) {
                        askModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
    <script>
        function updateStarRating(starElement) {
            const stars = document.querySelectorAll('.star-rating');
            const selectedValue = starElement.parentNode.querySelector('input').value;

            stars.forEach((star, index) => {
                if (index < selectedValue) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-500');
                } else {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-gray-300');
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Review Form Toggle
            const toggleReviewForm = (btn) => {
                const reviewFormContainer = document.getElementById('reviewFormContainer');
                const firstReviewBtn = document.getElementById('firstReviewBtn');

                if (reviewFormContainer) {
                    reviewFormContainer.classList.toggle('hidden');

                    // Scroll to review form
                    if (!reviewFormContainer.classList.contains('hidden')) {
                        reviewFormContainer.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            };

            // Event listeners for review buttons
            const writeReviewBtn = document.getElementById('writeReviewBtn');
            const firstReviewBtn = document.getElementById('firstReviewBtn');

            if (writeReviewBtn) {
                writeReviewBtn.addEventListener('click', () => toggleReviewForm(writeReviewBtn));
            }

            if (firstReviewBtn) {
                firstReviewBtn.addEventListener('click', () => toggleReviewForm(firstReviewBtn));
            }

            // Star Rating Interaction (same as previous implementation)
            const starRatingInput = document.getElementById('starRatingInput');
            if (starRatingInput) {
                const stars = starRatingInput.querySelectorAll('.star-icon');

                stars.forEach(star => {
                    star.addEventListener('mouseover', function() {
                        const value = this.getAttribute('data-value');
                        stars.forEach((s, index) => {
                            if (index < value) {
                                s.classList.remove('text-gray-300',
                                    'hover:text-yellow-400');
                                s.classList.add('text-yellow-500');
                            } else {
                                s.classList.remove('text-yellow-500');
                                s.classList.add('text-gray-300', 'hover:text-yellow-400');
                            }
                        });
                    });

                    star.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        this.closest('label').querySelector('input').checked = true;
                    });
                });

                // Reset stars when mouse leaves
                starRatingInput.addEventListener('mouseleave', function() {
                    const checkedStar = this.querySelector('input:checked');
                    if (checkedStar) {
                        const value = checkedStar.value;
                        stars.forEach((s, index) => {
                            if (index < value) {
                                s.classList.remove('text-gray-300', 'hover:text-yellow-400');
                                s.classList.add('text-yellow-500');
                            } else {
                                s.classList.remove('text-yellow-500');
                                s.classList.add('text-gray-300', 'hover:text-yellow-400');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush
