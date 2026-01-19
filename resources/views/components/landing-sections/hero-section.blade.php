<!-- Hero Banner Section -->
<section class="bg-gradient-red min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div
        class="absolute top-10 right-10 w-64 h-64 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"
        style="background-color: var(--secondary-bg); animation-delay: 2s;"></div>
    <div class="absolute bottom-10 left-10 w-64 h-64 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"
        style="background-color: var(--primary-bg);"></div>

    <div class="w-full relative z-10">
        <div class="max-w-2xl mx-auto text-center mb-6">
            <div class="inline-block mb-4">
                <span
                    class="inline-block px-6 py-2 rounded-full font-bold text-sm md:text-base animate-slide-in-up"
                    style="background-color: var(--secondary-bg); color: var(--secondary-text);">
                    {{ $section['data']['badge_text'] ?? '⚡ এক্সক্লুসিভ অফার চলছে' }}
                </span>
            </div>
            <h1 class="text-5xl md:text-7xl font-black mb-4 drop-shadow-2xl animate-slide-in-up"
                style="color: var(--secondary-bg); animation-delay: 0.2s;">
                {{ $common['hero_title'] ?? 'কম্বো প্যাকেজ থাকছে এখন!' }}
            </h1>
            <div class="dashed-border mx-auto w-3/4 mb-4" style="border-color: var(--secondary-bg);"></div>
        </div>

        <div class="max-w-2xl mx-auto text-center mb-8">
            <h2 class="text-2xl md:text-4xl font-bold drop-shadow-lg animate-slide-in-up"
                style="color: var(--primary-text); animation-delay: 0.3s;">
                🎯 {{ $common['hero_subtitle'] ?? '១८ रকमের ভিन্ন ভিন্ন স্বাদের' }} <br>
                <span
                    class="text-3xl md:text-5xl" style="color: var(--secondary-bg);">{{ $common['hero_highlight'] ?? '२१०पिस आचार' }}</span>
            </h2>
        </div>

        <div class="mb-8 animate-slide-in-up px-4" style="animation-delay: 0.4s;">
            <div class="rounded-3xl shadow-2xl relative border-8 border-dashed card-hover"
                style="min-height: 400px; max-height: 600px; border-color: var(--secondary-bg);">
                <img src="{{ $section['data']['background_image'] ? (strpos($section['data']['background_image'], 'http') === 0 ? $section['data']['background_image'] : asset($section['data']['background_image'])) : 'https://placehold.co/1200x600.png?text=কম্বো+প্যাকেজ' }}"
                    alt="কম্বো প্যাকেজ" class="w-full h-full rounded-2xl image-overlay" style="object-fit: cover;">
                <div class="badge-premium" style="background: linear-gradient(135deg, var(--secondary-bg), var(--secondary-bg)); color: var(--secondary-text);">
                    {{ $common['offer_percent'] ?? '35%' }} ছাড়!
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto text-center mt-12 space-y-4 animate-slide-in-up" style="animation-delay: 0.5s;">
            <a href="{{ $section['data']['cta_link'] ?? '#' }}"
                class="inline-flex items-center gap-3 font-black text-3xl px-12 py-5 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 btn-glow animate-glow"
                style="background-color: var(--secondary-bg); color: var(--secondary-text);">
                <span class="text-2xl">🛒</span>
                {{ $section['data']['cta_text'] ?? 'অর্ডার করতে চাই' }}
            </a>
        </div>

        <div class="max-w-2xl mx-auto text-center mt-6 animate-slide-in-up" style="animation-delay: 0.6s;">
            <a href="tel:{{ $common['phone_1'] ?? '0160616xxxx' }}"
                class="inline-flex items-center gap-3 bg-white hover:bg-gray-100 font-bold text-xl md:text-2xl px-10 py-4 rounded-full shadow-xl transform hover:scale-105 transition-all duration-300 card-hover"
                style="color: var(--primary-bg);">
                <span class="text-2xl">📞</span>
                {{ $common['phone_1'] ?? '0160616xxx' }}
            </a>
        </div>

        <div
            class="max-w-2xl mx-auto mt-12 flex justify-center gap-4 md:gap-8 text-center text-sm md:text-base"
            style="color: var(--primary-text);">
            <div class="animate-slide-in-left" style="animation-delay: 0.7s;">
                <div class="text-2xl mb-1">✅</div>
                <p>কোনো এডভান্স নেই</p>
            </div>
            <div class="animate-slide-in-up" style="animation-delay: 0.8s;">
                <div class="text-2xl mb-1">🚀</div>
                <p>দ্রুত ডেলিভারি</p>
            </div>
            <div class="animate-slide-in-right" style="animation-delay: 0.9s;">
                <div class="text-2xl mb-1">💯</div>
                <p>১০০% সন্তুষ্টি</p>
            </div>
        </div>
    </div>
</section>
