<!-- Testimonials Section -->
<section class="py-20 px-4 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 right-10 w-80 h-80 rounded-full mix-blend-multiply filter blur-3xl" style="background-color: var(--primary-bg);"></div>
    </div>

    <div class="max-w-6xl mx-auto relative z-10">
        <h2 class="text-4xl md:text-5xl font-black text-gradient text-center mb-4 animate-slide-in-up">
            {{ $section['data']['title'] ?? '⭐ আমাদের সন্তুষ্ট গ্রাহকরা' }}
        </h2>
        <p class="text-center text-gray-600 text-lg mb-12 animate-slide-in-up" style="animation-delay: 0.1s;">
            {{ $section['data']['subtitle'] ?? 'হাজারো খুশি গ্রাহক আমাদের বিশ্বাস করেন' }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach ($section['data']['testimonials'] ?? [] as $index => $testimonial)
                <div class="animate-slide-in-up stagger-{{ min($index + 1, 6) }}"
                    style="animation-delay: {{ 0.2 + $index * 0.05 }}s;">
                    <div
                        class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform card-hover group h-64">
                        <img src="{{ $testimonial['image'] ? (strpos($testimonial['image'], 'http') === 0 ? $testimonial['image'] : asset($testimonial['image'])) : 'https://placehold.co/400x400.png' }}" alt="গ্রাহক"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <div>
                                <p class="font-black text-lg" style="color: var(--secondary-bg);">{{ $testimonial['rating'] ?? '⭐⭐⭐⭐⭐' }}
                                </p>
                                <p class="text-white font-bold">{{ $testimonial['text'] ?? 'Great!' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="#"
                class="inline-flex items-center gap-3 font-black text-2xl md:text-3xl px-12 py-5 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 btn-glow animate-glow"
                style="background-color: var(--primary-bg); color: var(--primary-text);">
                <span class="text-3xl">🛒</span>
                এখনই অর্ডার করুন
            </a>
        </div>
    </div>
</section>
