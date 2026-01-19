@extends('frontend.layouts.app')
@push('styles')
    <style>
        @keyframes scissors-cut {
            0% {
                transform: translateX(-50%) rotate(0deg);
            }

            50% {
                transform: translateX(-50%) rotate(-12deg);
            }

            100% {
                transform: translateX(-50%) rotate(0deg);
            }
        }

        .qodef-svg--scissors {
            animation: scissors-cut 0.6s infinite;
        }
    </style>
    <style>
        .icon-badge {
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            transition: transform 0.3s ease;
        }

        .icon-badge:hover {
            transform: translateY(-10px) scale(1.05);
        }

        .counter-number {
            font-family: 'Georgia', serif;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .swiper-pagination-bullet {
            background: #999;
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: #000;
        }
    </style>
@endpush
@section('content')
    <section class="py-3 border-b bg-red-700 text-white fixed w-full z-30">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold">
                Top Ten Points – Tailor & Fabrics
            </h1>
        </div>
    </section>
    <x-hero-slider />
    <section class="bg-[#fdf8f3] py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="h-[280px] md:h-[360px] lg:h-[450px] overflow-hidden">
                    <img src="{{ asset('2.jpg') }}" alt="Tailor portrait" class="rounded-sm shadow-lg grayscale">
                </div>
                {{-- <div class="relative w-full py-16">
                    <div class="relative border-t border-dashed border-gray-400"></div>
                    <div class="absolute inset-0 flex justify-center items-start pointer-events-none">
                        <svg class="qodef-svg--scissors w-20 h-auto text-gray-700 bg-white px-2"
                            xmlns="http://www.w3.org/2000/svg" viewBox="-10 0 75 30.19" fill="currentColor">
                            <g class="qode--1">
                                <path d="M49.91,7.83c-0.47,0.05-1,0.11-1.54,0.21c-0.14,0.02-0.27,0.05-0.41,0.08s-0.28,0.06-0.41,0.09
                    c-3.64,0.81-15.66,8.45-22.62,12.96l4.8,3.57L52.56,7.74z" />
                                <path d="M22.56,30.06l1.2-0.89c-0.56-0.36-1.08-0.7-1.56-1.01
                    c-0.82-0.53-1.48-0.97-1.96-1.27l-0.2-0.13l-0.07-0.22
                    c-0.06-0.17-0.12-0.34-0.18-0.51
                    c-0.4,0.86-0.91,1.81-1.57,2.79
                    c-0.31,0.45-0.62,0.86-0.93,1.25H22.56z" />
                            </g>

                            <g class="qode--2">
                                <path d="M5.56,12.24c3.39,1.22,5.87,2.82,7.45,4.02
                    c1.47,1.11,3.42,2.61,5.2,5.22
                    c1.26,1.86,1.98,3.61,2.39,4.84
                    c1.08,0.69,3.18,2.07,5.76,3.73h9.4
                    L11.83,12.25c1.48-0.61,2.63-1.69,3.16-3.14
                    c1.17-3.2-1.16-6.99-5.21-8.46
                    C5.74-0.83,1.52,0.57,0.35,3.78
                    C-0.82,6.98,1.51,10.77,5.56,12.24z" />
                                <path fill="#fff" d="M23.6,26.23c0.23,0.17,0.51,0.28,0.82,0.28
                    c0.75,0,1.35-0.6,1.35-1.35
                    c0-0.31-0.1-0.59-0.28-0.82L23.6,26.23z" />
                                <path fill="#fff" d="M25.23,24.08c-0.23-0.17-0.51-0.28-0.82-0.28
                    c-0.75,0-1.35,0.6-1.35,1.35
                    c0,0.31,0.1,0.59,0.28,0.82L25.23,24.08z" />
                            </g>
                        </svg>
                    </div>
                </div> --}}

                {{-- Right Content --}}
                <div class="space-y-6">
                    <span class="text-sm font-semibold tracking-widest text-red-700 uppercase">
                        About Tailoring
                    </span>

                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                        Every day is a fashion show &amp; the world is your runway
                    </h2>

                    <p class="text-gray-600 leading-relaxed">
                        Felis dignissim cursus libero parturient leo purus cubilia risus habitant.
                        Tempor hac gravida. Vivamus proin felis vehicula venenatis vel mi lorem
                        ornare adipiscing.
                    </p>

                    <p class="text-gray-600 leading-relaxed">
                        Luctus dolor molestie elementum fames augue praesent magnis metus feugiat.
                        Justo faucibus magnis potenti feugiat imperdiet nisi.
                    </p>

                    {{-- Watch Intro --}}
                    <div class="flex items-center gap-4 pt-4">
                        <button
                            class="w-14 h-14 rounded-full bg-[#d6a35c] flex items-center justify-center shadow-lg hover:scale-105 transition">
                            ▶
                        </button>

                        <span class="font-semibold text-gray-900">
                            Watch The Intro
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="py-10 bg-red-700 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">

            <!-- Heading -->
            <h2 class="text-3xl md:text-4xl font-medium mb-6">
                Best Of Our Services
            </h2>
            {{-- <div class="flex items-center justify-center mb-8">
                <span class="h-px w-16"></span>
                <span class="mx-4 text-xl">✂</span>
                <span class="h-px w-16"></span>
            </div> --}}
            <p class="max-w-3xl mx-auto leading-relaxed">
                Mollis consequat taciti nullam hac mattis, molestie sociis sodales
                aliquam penatibus tincidunt. Ridiculus. Magnis, aliquam ad
                vestibulum ac pellentesque inceptos sem.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach ([['title' => 'Alteration', 'icon' => '🧵'], ['title' => 'Styling', 'icon' => '🧥'], ['title' => 'Concierge', 'icon' => '🧑‍✈️'], ['title' => 'Modeling', 'icon' => '👗']] as $service)
                    <div class="text-center group">
                        <div class="relative mx-auto w-24 h-24">
                            <div class="absolute inset-0 rotate-45 rounded-sm"></div>
                            <div class="relative z-10 flex h-full w-full items-center justify-center text-5xl">
                                {{ $service['icon'] }}
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-medium mb-4">
                            {{ $service['title'] }}
                        </h3>

                        <!-- Text -->
                        <p class="text-sm leading-relaxed mb-8">
                            Fermentum penatibus taciti quisque parturient convallis
                            vel nonummy ac phasellus luctus scelerisque.
                        </p>
                        {{-- <a href="#"
                            class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-amber-400 transition hover:bg-amber-400 hover:text-white">
                            →
                        </a> --}}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="relative py-20 px-4 flex items-center bg-cover bg-center"
        style="background-image: url('https://toptenone.com/wp-content/uploads/2024/06/423312787_900283981883431_5487917799191107685_n-2.jpg');">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="container mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 max-w-6xl mx-auto">
                <!-- Counter Item 1 -->
                <div class="flex flex-col items-center text-center">
                    <div class="icon-badge w-32 h-32 flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-medium mb-3 tracking-wide">Available Blazer Design</h3>
                    <div class="counter-number text-6xl text-white" data-target="82">0</div>
                </div>

                <!-- Counter Item 2 -->
                <div class="flex flex-col items-center text-center">
                    <div class="icon-badge w-32 h-32 flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-medium mb-3 tracking-wide">Mens Suit Design</h3>
                    <div class="counter-number text-6xl text-white" data-target="35">0</div>
                </div>

                <!-- Counter Item 3 -->
                <div class="flex flex-col items-center text-center">
                    <div class="icon-badge w-32 h-32 flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-medium mb-3 tracking-wide">Women Winter Cloth</h3>
                    <div class="counter-number text-6xl text-white" data-target="18">0</div>
                </div>

                <!-- Counter Item 4 -->
                <div class="flex flex-col items-center text-center">
                    <div class="icon-badge w-32 h-32 flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-medium mb-3 tracking-wide">Model Fashion Dummy</h3>
                    <div class="counter-number text-6xl text-white" data-target="105">0</div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">

            <div class="swiper tailorSwiper">
                <div class="swiper-wrapper">

                    <!-- Slide 1 -->
                    <div class="swiper-slide text-center">
                        <img src="https://mohair.qodeinteractive.com/wp-content/uploads/2023/05/main-home-png-2-600x900.png"
                            class="mx-auto h-[420px] w-[280px] object-cover rounded-none" alt="" />
                        <h4 class="mt-6 text-lg font-medium">Body Measure</h4>
                        <p class="italic text-sm text-gray-500">Custom Tailored</p>
                    </div>
                    <div class="swiper-slide text-center">
                        <img src="https://mohair.qodeinteractive.com/wp-content/uploads/2023/05/main-home-png-2-600x900.png"
                            class="mx-auto h-[420px] w-[280px] object-cover rounded-none" alt="" />
                        <h4 class="mt-6 text-lg font-medium">Body Measure</h4>
                        <p class="italic text-sm text-gray-500">Custom Tailored</p>
                    </div>

                    <!-- Slide 2 (Rounded) -->
                    <div class="swiper-slide text-center">
                        <img src="https://mohair.qodeinteractive.com/wp-content/uploads/2023/05/main-home-png-2-600x900.png"
                            class="mx-auto h-[420px] w-[280px] object-cover" alt="" />
                        <h4 class="mt-6 text-lg font-medium">Dress Tailor</h4>
                        <p class="italic text-sm text-gray-500">Dress Tailor</p>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide text-center">
                        <img src="https://mohair.qodeinteractive.com/wp-content/uploads/2023/05/main-home-png-2-600x900.png"
                            class="mx-auto h-[420px] w-[280px] object-cover rounded-none" alt="" />
                        <h4 class="mt-6 text-lg font-medium">Design Sketches</h4>
                        <p class="italic text-sm text-gray-500">Design</p>
                    </div>

                    <!-- Slide 4 (Rounded) -->
                    <div class="swiper-slide text-center">
                        <img src="https://mohair.qodeinteractive.com/wp-content/uploads/2023/05/main-home-png-2-600x900.png"
                            class="mx-auto h-[420px] w-[280px] object-cover" alt="" />
                        <h4 class="mt-6 text-lg font-medium">Handmade</h4>
                        <p class="italic text-sm text-gray-500">Handmade</p>
                    </div>

                </div>
                <div class="swiper-pagination mt-28"></div>
            </div>
        </div>
    </section>
    <footer class="border-t py-10 mt-12 bg-red-800 text-white">
        <div class="container mx-auto text-center space-y-3">
            <h3 class="text-lg font-semibold">
                Top Ten Points
            </h3>

            <p class="text-sm">
                Professional Tailoring & Premium Fabrics
            </p>

            <address class="not-italic text-sm">
                Mawla Tower, Noakhali Zilla School Opposite,<br>
                Maijdee Court, Noakhali.
            </address>

            <p class="text-sm">
                Contact: +88001721-602230 | +8801837-498209 | toptenpoint@gmail.com
            </p>

            <p class="text-xs">
                © {{ date('Y') }} Top Ten Points. All rights reserved. Developed by
                <a href="https://bestsheba.com/" target="_blank" class="text-blue-300">Best Sheba</a>    
            </p>
        </div>
    </footer>
    <div class="fixed bottom-8 right-5 z-[9999] flex flex-col gap-3">
        <a href="https://wa.me/+8801837498209" target="_blank"
            class="rounded-full w-[50px] h-[50px] flex justify-center items-center bg-green-500 text-white relative shadow-lg hover:bg-green-600 transition-colors">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
        </a>
        <a href="tel:+88001721602230"
            class="rounded-full w-[50px] h-[50px] flex justify-center items-center bg-blue-500 text-white shadow-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-phone text-xl"></i>
        </a>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        function heroSlider() {
            return {
                active: 0,
                interval: null,
                slides: [{
                        image: '/hero1.jpg',
                        tag: "MODERN STYLE",
                        title: 'Designed for confidence',
                        subtitle: 'Premium tailoring crafted for everyday elegance',
                    },
                    {
                        image: 'https://toptenone.com/wp-content/uploads/2024/06/423312787_900283981883431_5487917799191107685_n-2.jpg',
                        tag: "CLASSIC FIT",
                        title: 'Timeless menswear',
                        subtitle: 'Crafted details, perfect proportions',
                    },
                    {
                        image: '/hero1.webp',
                        tag: "MAN'S TAILOR",
                        title: 'Never goes out of style',
                        subtitle: 'We are a mid-tier menswear company aiming for the 20–35 market',
                    },
                ],
                start() {
                    this.interval = setInterval(() => {
                        this.next()
                    }, 5000)
                },
                next() {
                    this.active = (this.active + 1) % this.slides.length
                },
                prev() {
                    this.active =
                        this.active === 0 ? this.slides.length - 1 : this.active - 1
                },
            }
        }
    </script>
    <script>
        const scissors = document.getElementById("scissors");
        const bladeTop = document.getElementById("blade-top");
        const bladeBottom = document.getElementById("blade-bottom");
        const line = document.getElementById("cut-line");

        let pos = 0;
        let open = true;

        function cut() {
            const maxWidth = line.offsetWidth;

            // Move scissors
            pos += 2;
            scissors.style.left = pos + "px";

            // Blade animation (open / close)
            if (open) {
                bladeTop.style.transform = "rotate(-18deg)";
                bladeBottom.style.transform = "rotate(18deg)";
            } else {
                bladeTop.style.transform = "rotate(0deg)";
                bladeBottom.style.transform = "rotate(0deg)";
            }
            open = !open;

            // Reveal cut line
            line.style.backgroundSize = `${pos}px 100%`;

            if (pos < maxWidth - 40) {
                requestAnimationFrame(cut);
            }
        }

        cut();
    </script>
    <script>
        // Counter animation function
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Intersection Observer for triggering animation on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    animateCounter(entry.target, target);
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        // Observe all counter elements
        document.querySelectorAll('.counter-number').forEach(counter => {
            observer.observe(counter);
        });
    </script>
    <script>
        new Swiper(".tailorSwiper", {
            loop: true,
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
        });
    </script>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush
