@extends('frontend.layouts.user')
@section('title', 'Wishlist')
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
            Wishlist
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
                    Wishlist
                </span>
            </a>
            <div class="mx-auto w-full">
                @forelse ($favorites as $product)
                    <div class="mb-2">
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                <a href="{{ route('product.details', $product->slug) }}" class="shrink-0 md:order-1">
                                    <img class="h-20 w-20" src="{{ $product->picture_url }}" alt="{{ $product->name }}" />
                                </a>
                                <label for="counter-input" class="sr-only">
                                    Choose quantity:
                                </label>
                                <div class="flex items-center justify-between md:order-3 md:justify-end">
                                    <div class="text-end md:order-4 md:w-32">
                                        <p class="text-base font-bold text-black">
                                            {{ showAmount($product->price) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                    <div class="leading-none">
                                        <a href="{{ route('product.details', $product->slug) }}"
                                            class="text-base font-medium text-black hover:underline leading-none">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <button onclick="toggleFavorite('{{ $product->id }}')" type="submit"
                                            class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                            <svg class="me-1.5 h-5 w-5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400">
                        No data found...
                    </div>
                @endforelse
            </div>
            {{ $favorites->links('pagination::tailwind') }}
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection
