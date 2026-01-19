<!-- Why Order Section -->
<section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(to right, #111827, var(--primary-bg), #111827);">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl" style="background-color: var(--secondary-bg);">
        </div>
        <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl" style="background-color: var(--primary-bg);"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        <div class="border-8 rounded-3xl overflow-hidden shadow-2xl relative" style="border-color: var(--secondary-bg);">
            <div class="bg-gradient-red py-8 px-6 relative" style="color: var(--primary-text);">
                <h2 class="text-3xl md:text-4xl font-black text-center">
                    {{ $section['data']['title'] ?? '✨ কেন আমাদের কাছ থেকে অর্ডার করবেন?' }}
                </h2>
            </div>

            <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-6 md:p-10">
                <div class="space-y-6">
                    @foreach ($section['data']['features'] ?? [] as $index => $feature)
                        <div class="flex items-start gap-4 bg-gradient-to-r p-5 rounded-xl shadow-lg hover:shadow-xl transition-all feature-item animate-slide-in-up stagger-{{ $index + 1 }}"
                            style="background: linear-gradient(to right, var(--primary-bg), var(--primary-bg)); color: var(--primary-text); animation-delay: {{ 0.1 + $index * 0.05 }}s;">
                            <div class="flex-shrink-0 text-2xl">{{ $feature['icon'] ?? '💡' }}</div>
                            <div class="flex-1">
                                <p class="text-lg md:text-xl font-bold">{{ $feature['title'] ?? 'Feature' }}
                                </p>
                                <p class="text-sm mt-1" style="color: var(--primary-text); opacity: 0.9;">{{ $feature['description'] ?? 'Description' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-10 text-center space-y-6">
            <a href="#"
                class="inline-flex items-center gap-3 font-black text-2xl md:text-3xl px-10 py-4 rounded-full shadow-xl transform hover:scale-110 transition-all duration-300 btn-glow"
                style="background-color: var(--secondary-bg); color: var(--secondary-text);">
                <span class="text-2xl">📧</span>
                আমাদের সাথে যোগাযোগ করুন
            </a>

            <div>
                <a href="tel:{{ $common['phone_2'] ?? '01864444xxxx' }}"
                    class="inline-flex items-center gap-3 bg-white hover:bg-gray-100 font-bold text-2xl md:text-3xl px-10 py-4 rounded-full shadow-xl transform hover:scale-110 transition-all duration-300 btn-glow"
                    style="color: var(--primary-bg);">
                    <span class="text-2xl">📞</span>
                    {{ $common['phone_2'] ?? '01864444xxx' }}
                </a>
            </div>
        </div>
    </div>
</section>
