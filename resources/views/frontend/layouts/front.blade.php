<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') {{ $settings?->website_name }}</title>
    <x-favicon-icon />
    @include('frontend.layouts.links')
    <style>
        :root {
            --primary: {{ $settings->primary_color }};
            --secondary: {{ $settings->secondary_color }};
            --text-color: {{ $settings->text_color }};
        }
    </style>
    @livewireStyles
    <x-meta-pixel />
    <x-google-tag />
</head>

<body class="">
    @include('frontend.layouts.top-bar')
    <div>
        @yield('content')
    </div>

    <footer class="bg-primary text-white">
        <div class="container mx-auto p-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-5 md:grid-cols-3">
                <div>
                    <img src="{{ asset($settings->footer_logo ?? $settings->website_logo) }}" class="w-24"
                        alt="Logo">
                    <div class="space-y-2 text-sm mt-3">
                        <p class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $settings->address ?? '' }}
                        </p>
                        <p class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $settings->phone_number ?? '' }}
                        </p>
                        <p class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $settings->email ?? '' }}
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">USEFUL LINKS</h3>
                    <nav class="space-y-2 text-sm">
                        <p><a href="#" class="hover:text-gray-300">About Us</a></p>
                        <p><a href="#" class="hover:text-gray-300">Privacy Policy</a></p>
                        <p><a href="#" class="hover:text-gray-300">Cookie Policy</a></p>
                        <p><a href="#" class="hover:text-gray-300">Terms & Conditions</a></p>
                        <p><a href="#" class="hover:text-gray-300">Why Shop With Us</a></p>
                    </nav>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">HELP</h3>
                    <nav class="space-y-2 text-sm">
                        <p><a href="#" class="hover:text-gray-300">Payment</a></p>
                        <p><a href="#" class="hover:text-gray-300">Shipping</a></p>
                        <p><a href="#" class="hover:text-gray-300">Return And Replacement</a></p>
                        <p><a href="#" class="hover:text-gray-300">Chat With Us</a></p>
                        <p><a href="#" class="hover:text-gray-300">Support</a></p>
                    </nav>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">SOCIAL</h3>
                    <nav class="space-y-2 text-sm">
                        <p><a href="{{ $settings->facebook }}" class="hover:text-gray-300">Facebook</a></p>
                        <p><a href="{{ $settings->instagram }}" class="hover:text-gray-300">Instagram</a></p>
                        <p><a href="{{ $settings->youtube }}" class="hover:text-gray-300">Youtube</a></p>
                    </nav>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">DOWNLOAD</h3>
                    <div class="space-y-3">
                        <a href="#" class="block">
                            <img src="{{ asset('assets/frontend/images/googlePlay.jpg') }}" alt="Get it on Google Play"
                                class="h-10">
                        </a>
                        <a href="#" class="block">
                            <img src="{{ asset('assets/frontend/images/appStore.jpg') }}"
                                alt="Download on the App Store" class="h-10">
                        </a>
                        <a href="#" class="block">
                            <img src="{{ asset('/assets/frontend/images/appgallary.jpg') }}"
                                alt="Explore it on AppGallery" class="h-10">
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-600">
                <div class="flex justify-between items-center">
                    <p class="text-sm">Payment Methods</p>
                    <div class="flex gap-2">
                        @if ($settings->payment_logo)
                            <img src="{{ asset($settings->payment_logo) }}" alt="Payment methods" class="h-8">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="w-full mx-auto">
        <div class="bg-gray-100 mt-1 border-b ">
            <div class="py-5 text-center  max-w-screen-xl mx-auto">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} All Rights Reserved -
                    {{ $settings->website_name }}
                </p>
            </div>
        </div>
    </div>
    @include('frontend.layouts.md-bottom-menu')
    {{-- <a href="{{ route('cart') }}" class="fixed bottom-1/2 right-4 top-1/2 z-[99999] hidden md:block">
        <div class="rounded-t bg-primary text-white font-bold text-center ">
            <div>
                <i class="!text-2xl las la-shopping-bag"></i>
            </div>
            <div class="text-sm">
                {{ $items_in_user_cart }} {{ Str::plural('Item', $items_in_user_cart) }}
            </div>
        </div>
        <div class="text-sm font-bold rounded-b bg-yellow-500 text-white text-center px-3">
            {{ showAmount($user_cart_total) }}
        </div>
    </a> --}}
    @stack('scripts')
    <x-frontend.toggle-favorite-script />
    <x-frontend.add-to-cart-script />
    <x-toastr />
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PWLMWCJS" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <x-topbar-script />
    @livewireScripts
</body>

</html>
