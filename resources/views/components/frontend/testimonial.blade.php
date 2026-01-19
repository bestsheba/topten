@props(['testimonials'])

<div class="container">
    <h2 class="text-sm md:text-2xl font-medium text-gray-800 capitalize mb-4">
        What Customer Are Saying
    </h2>
    <div class="swiper testimonial-slide-carousel swiper-container">
        <div class="swiper-wrapper">
            @foreach ($testimonials as $testimonial)
                <div
                    class="swiper-slide bg-white shadow border rounded overflow-hidden group flex flex-col h-full p-8 text-center">
                    <p class="font-bold capitalize">
                        {{ $testimonial?->user?->name }}
                    </p>
                    <p class="text-xl text-gray-700">
                        {{ $testimonial?->description }}
                    </p>
                    <div class="flex items-center justify-center space-x-2 mt-4">
                        <x-frontend.product-rating :rating="$testimonial?->rating" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
