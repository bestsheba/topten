<!-- Package Options Section -->
<section class="py-20 px-4 bg-gradient-to-b from-white via-gray-50 to-white relative overflow-hidden">
    <div class="absolute top-0 left-5% w-48 h-48 rounded-full filter blur-2xl opacity-30" style="background-color: var(--primary-bg);"></div>
    <div class="absolute bottom-0 right-5% w-48 h-48 rounded-full filter blur-2xl opacity-30" style="background-color: var(--secondary-bg);"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="bg-white rounded-3xl p-8 shadow-2xl border-4 border-gray-200 relative overflow-hidden">
            <h2 class="text-4xl md:text-5xl font-black text-gradient text-center mb-4 animate-slide-in-up">
                {{ $section['data']['title'] ?? '🎁 কম্বো প্যাকেজে থাকছেঃ' }}
            </h2>
            <p class="text-center text-gray-600 text-lg mb-10 animate-slide-in-up" style="animation-delay: 0.1s;">
                {{ $section['data']['subtitle'] ?? 'সব ধরনের আচারের স্বাদ একসাথে পান' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($section['data']['packages'] ?? [] as $index => $package)
                    <div class="animate-slide-in-up stagger-{{ $index + 1 }}"
                        style="animation-delay: {{ 0.2 + $index * 0.1 }}s;">
                        <div
                            class="border-4 border-black rounded-2xl p-5 bg-white hover:shadow-2xl transition-all duration-300 transform card-hover relative overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-10 transition-opacity"
                                style="background: linear-gradient(to bottom right, var(--primary-bg), transparent);">
                            </div>
                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-3 mb-4 relative">
                                <img src="{{ $package['image'] ? (strpos($package['image'], 'http') === 0 ? $package['image'] : asset($package['image'])) : 'https://placehold.co/400x400.png' }}"
                                    alt="{{ $package['name'] ?? 'Package' }}"
                                    class="w-full h-64 object-cover rounded-lg image-overlay">
                            </div>
                            <h3
                                class="text-2xl font-bold text-center text-gray-800 transition packages-hover"
                                style="--hover-color: var(--primary-bg);">
                                {{ $package['name'] ?? 'Package' }}
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
