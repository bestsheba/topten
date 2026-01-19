<footer class="bg-primary text-text-primary">
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-5 md:grid-cols-3">
            <div>
                <img src="{{ asset($settings->footer_logo ?? $settings->website_logo) }}" class="w-24" alt="Logo">
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
                    @php
                        $usefulLinksGroup = $custom_page_groups->where('name', 'Useful Links')->first();
                    @endphp
                    @if($usefulLinksGroup && $usefulLinksGroup->pages->count() > 0)
                        @foreach($usefulLinksGroup->pages as $page)
                            <p>
                                @if($page->link)
                                    <a href="{{ $page->link }}" target="_blank" class="hover:text-gray-300">{{ $page->title }}</a>
                                @else
                                    <a href="/page/{{ $page->slug }}" class="hover:text-gray-300">{{ $page->title }}</a>
                                @endif
                            </p>
                        @endforeach
                    @else
                        {{-- Fallback to hardcoded links if no custom pages --}}
                        <p><a href="/page/privacy-policy" class="hover:text-gray-300">Privacy Policy</a></p>
                        <p><a href="/page/terms-and-conditions" class="hover:text-gray-300">Terms & Conditions</a></p>
                        <p><a href="/page/return-and-refund-policy" class="hover:text-gray-300">Return & Refund Policy</a></p>
                    @endif
                    @if ($settings->affiliate_feature_enabled && (!auth()->check() || !auth()->user()?->is_affiliate))
                        <p>
                            <a href="{{ route('affiliate.register') }}" class="hover:text-gray-300">
                                Affiliate Programme
                            </a>
                        </p>
                    @endif
                </nav>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-4">HELP</h3>
                <nav class="space-y-2 text-sm">
                    @php
                        $helpGroup = $custom_page_groups->where('name', 'Help')->first();
                    @endphp
                    @if($helpGroup && $helpGroup->pages->count() > 0)
                        @foreach($helpGroup->pages as $page)
                            <p>
                                @if($page->link)
                                    <a href="{{ $page->link }}" target="_blank" class="hover:text-gray-300">{{ $page->title }}</a>
                                @else
                                    <a href="/page/{{ $page->slug }}" class="hover:text-gray-300">{{ $page->title }}</a>
                                @endif
                            </p>
                        @endforeach
                    @else
                        {{-- Fallback to hardcoded links if no custom pages --}}
                        <p><a href="#" class="hover:text-gray-300">Payment</a></p>
                        <p><a href="#" class="hover:text-gray-300">Shipping</a></p>
                        <p><a href="#" class="hover:text-gray-300">Return And Replacement</a></p>
                        <p><a href="#" class="hover:text-gray-300">Chat With Us</a></p>
                        <p><a href="#" class="hover:text-gray-300">Support</a></p>
                    @endif
                </nav>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-4">SOCIAL</h3>
                <nav class="space-y-2 text-sm">
                    @php
                        $socialGroup = $custom_page_groups->where('name', 'Social')->first();
                    @endphp
                    @if($socialGroup && $socialGroup->pages->count() > 0)
                        @foreach($socialGroup->pages as $page)
                            <p>
                                @if($page->link)
                                    <a href="{{ $page->link }}" target="_blank" class="hover:text-gray-300">{{ $page->title }}</a>
                                @else
                                    <a href="/page/{{ $page->slug }}" class="hover:text-gray-300">{{ $page->title }}</a>
                                @endif
                            </p>
                        @endforeach
                    @else
                        {{-- Fallback to settings social links if no custom pages --}}
                        @if($settings->facebook)
                            <p><a href="{{ $settings->facebook }}" target="_blank" class="hover:text-gray-300">Facebook</a></p>
                        @endif
                        @if($settings->instagram)
                            <p><a href="{{ $settings->instagram }}" target="_blank" class="hover:text-gray-300">Instagram</a></p>
                        @endif
                        @if($settings->youtube)
                            <p><a href="{{ $settings->youtube }}" target="_blank" class="hover:text-gray-300">Youtube</a></p>
                        @endif
                    @endif
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
                        <img src="{{ asset('assets/frontend/images/appStore.jpg') }}" alt="Download on the App Store"
                            class="h-10">
                    </a>
                    <a href="#" class="block">
                        <img src="{{ asset('/assets/frontend/images/appgallary.jpg') }}" alt="Explore it on AppGallery"
                            class="h-10">
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-text-primary">
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
<div class="w-full mx-auto mb-[70px] lg:mb-0" id="footerLast">
    <div class="bg-gray-100 mt-1 border-b ">
        <div class="py-5 text-center  max-w-screen-xl mx-auto">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} All Rights Reserved -
                {{ $settings->website_name }}
            </p>
        </div>
    </div>
</div>
<!-- mobile screen bottom menu -->
@include('frontend.layouts.md-bottom-menu')
<!-- mobile screen bottom menu end-->
