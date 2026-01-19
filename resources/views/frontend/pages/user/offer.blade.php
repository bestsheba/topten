@extends('frontend.layouts.app')
@section('title', 'Offer')
@section('content')
    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">
            Offer
        </p>
    </div>
    <!-- ./breadcrumb -->

    <!-- wrapper -->
    <div class="container grid grid-cols-1 md:grid-cols-12 items-start gap-6 pt-4 pb-16">

        <!-- sidebar -->
        {{-- @include('frontend.pages.user.sidebar') --}}
        <!-- ./sidebar -->

        <!-- info -->
        <div class="col-span-full md:col-span-full shadow rounded px-6 pt-5 pb-7">
            <a href="{{ route('user.account.menu') }}" class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
                <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
                <span>
                    Offer
                </span>
            </a>
            <div class="mx-auto w-full">
                {{-- @forelse ($orders as $order) --}}
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <div class="pt-4" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                            <ul role="list" class="divide-y divide-gray-200">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0">
                                            <img class="flex-shrink-0 w-10 h-10"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/products/iphone.png"
                                                alt="imac image">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 truncate">
                                                    iPhone 14 Pro
                                                </p>
                                                <div
                                                    class="flex items-center justify-end flex-1 text-sm text-green-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                                            d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                                        </path>
                                                    </svg>
                                                    2.5%
                                                    <span class="ml-2 text-gray-500">vs last month</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $445,467
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0">
                                            <img class="flex-shrink-0 w-10 h-10"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/products/imac.png"
                                                alt="imac image">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 truncate">
                                                    Apple iMac 27"
                                                </p>
                                                <div
                                                    class="flex items-center justify-end flex-1 text-sm text-green-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                                            d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                                        </path>
                                                    </svg>
                                                    12.5%
                                                    <span class="ml-2 text-gray-500">vs last month</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $256,982
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0">
                                            <img class="flex-shrink-0 w-10 h-10"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/products/watch.png"
                                                alt="watch image">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 truncate">
                                                    Apple Watch SE
                                                </p>
                                                <div
                                                    class="flex items-center justify-end flex-1 text-sm text-red-600 dark:text-red-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                                            d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z">
                                                        </path>
                                                    </svg>
                                                    1.35%
                                                    <span class="ml-2 text-gray-500">vs last month</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $201,869
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0">
                                            <img class="flex-shrink-0 w-10 h-10"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/products/ipad.png"
                                                alt="ipad image">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 truncate">
                                                    Apple iPad Air
                                                </p>
                                                <div
                                                    class="flex items-center justify-end flex-1 text-sm text-green-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                                            d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                                        </path>
                                                    </svg>
                                                    12.5%
                                                    <span class="ml-2 text-gray-500">vs last month</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $103,967
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0">
                                            <img class="flex-shrink-0 w-10 h-10"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/products/imac.png"
                                                alt="imac image">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 truncate">
                                                    Apple iMac 24"
                                                </p>
                                                <div
                                                    class="flex items-center justify-end flex-1 text-sm text-red-600 dark:text-red-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                                            d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z">
                                                        </path>
                                                    </svg>
                                                    2%
                                                    <span class="ml-2 text-gray-500">vs last month</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $98,543
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="hidden pt-4" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/users/neil-sims.png"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">
                                                Neil Sims
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                email@flowbite.com
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $3320
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/users/bonnie-green.png"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">
                                                Bonnie Green
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                email@flowbite.com
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $2467
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/users/michael-gough.png"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">
                                                Michael Gough
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                email@flowbite.com
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $2235
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/users/thomas-lean.png"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">
                                                Thomes Lean
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                email@flowbite.com
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $1842
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://flowbite-admin-dashboard.vercel.app/images/users/lana-byrd.png"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">
                                                Lana Byrd
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                email@flowbite.com
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            $1044
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- @empty
                    <div class="text-center text-gray-400">
                        No data found...
                    </div>
                @endforelse --}}
            </div>
        </div>
        <!-- ./info -->
    </div>
    <!-- ./wrapper -->
@endsection
