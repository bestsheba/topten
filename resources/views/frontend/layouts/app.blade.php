<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') {{ $settings?->website_name }}</title>
    <x-seo-tags />
    <x-favicon-icon />
    @livewireStyles
    @include('frontend.layouts.links')
    <x-meta-pixel />
    <x-google-tag />
    @stack('styles')
</head>

<body class="overflow-x-hidden">
    <main>
        @yield('content')
    </main>
    <x-frontend.toggle-favorite-script />
    <x-frontend.add-to-cart-script />
    <x-toastr />
    @stack('scripts')
    <x-topbar-script />
    @livewireScripts
</body>

</html>
