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
    @stack('styles')
    @livewireStyles
    <x-meta-pixel />
    <x-google-tag />
</head>

<body class="">
    @include('frontend.layouts.top-bar')
    <!-- Mobile sidebar trigger -->
    <div class="md:hidden sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-gray-100">
        <div class="px-4 py-3 flex items-center justify-between">
            <button id="openUserSidebar" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M3.75 5.25a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75z" clip-rule="evenodd"/></svg>
                Menu
            </button>
            @auth
                <div class="flex items-center gap-2">
                    <img src="{{ $user?->avatar_url ?? makeAvatar('User') }}" alt="{{ $user?->name ?? 'User' }}" class="rounded-full w-8 h-8 border border-gray-200 object-cover">
                    <span class="text-sm text-gray-800 font-medium">{{ $user?->name ?? 'User' }}</span>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile sidebar drawer -->
    <div id="userSidebarDrawer" class="md:hidden fixed inset-0 z-50 hidden">
        <div id="userSidebarBackdrop" class="absolute inset-0 bg-black/40"></div>
        <div class="absolute left-0 top-0 h-full w-80 max-w-[85%] bg-white shadow-xl flex flex-col">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Menu</h3>
                <button id="closeUserSidebar" type="button" class="rounded-md p-2 text-gray-600 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" d="M6.72 6.72a.75.75 0 011.06 0L12 10.94l4.22-4.22a.75.75 0 111.06 1.06L13.06 12l4.22 4.22a.75.75 0 11-1.06 1.06L12 13.06l-4.22 4.22a.75.75 0 11-1.06-1.06L10.94 12 6.72 7.78a.75.75 0 010-1.06z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto p-4">
                @auth
                    <div class="px-2 py-3 mb-4 shadow flex items-center gap-3 rounded-md">
                        <img src="{{ $user?->avatar_url ?? makeAvatar('User') }}" alt="{{ $user?->name ?? 'User' }}" class="rounded-full w-10 h-10 border border-gray-200 object-cover">
                        <div>
                            <p class="text-gray-600 text-xs">Hello,</p>
                            <h4 class="text-gray-800 text-sm font-medium">{{ $user?->name ?? 'User' }}</h4>
                        </div>
                    </div>
                @endauth

                @include('frontend.layouts.user-menu')
            </div>
        </div>
    </div>
    <div class="flex">
        <div class="w-72 hidden md:block">
            @auth
                <div class="px-4 py-3 shadow flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <img src="{{ $user?->avatar_url ?? makeAvatar('User') }}" alt="{{ $user?->name ?? 'User' }}"
                            class="rounded-full w-14 h-14 border border-gray-200 p-1 object-cover">
                    </div>
                    <div class="flex-grow">
                        <p class="text-gray-600">Hello,</p>
                        <h4 class="text-gray-800 font-medium">
                            {{ $user?->name ?? 'User' }}
                        </h4>
                    </div>
                </div>
            @endauth
            <div class="mt-6 bg-white shadow rounded p-4 divide-y divide-gray-200 space-y-4 text-gray-600">
                @include('frontend.layouts.user-menu')
            </div>
        </div>
        <div class="w-full mx-auto">
            @yield('content')
        </div>
    </div>
    @include('frontend.layouts.footer')

    @stack('scripts')
    <script>
        (function () {
            const openBtn = document.getElementById('openUserSidebar');
            const closeBtn = document.getElementById('closeUserSidebar');
            const drawer = document.getElementById('userSidebarDrawer');
            const backdrop = document.getElementById('userSidebarBackdrop');

            function openDrawer() {
                if (drawer) drawer.classList.remove('hidden');
            }

            function closeDrawer() {
                if (drawer) drawer.classList.add('hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);
        })();
    </script>
    <x-frontend.toggle-favorite-script />
    <x-frontend.add-to-cart-script />
    <x-toastr />
    <x-topbar-script />
    @livewireScripts
</body>

</html>
