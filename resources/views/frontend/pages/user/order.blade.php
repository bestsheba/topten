@extends('frontend.layouts.user')
@section('title', 'Order')
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
            Order
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
                    Order
                </span>
            </a>
            <div class="mx-auto w-full">
                @forelse ($orders as $order)
                    <div class="mb-2">
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                <div class="flex items-center justify-between md:order-3 md:justify-end">
                                    <div class="flex items-center">
                                        {{ showAmount($order->total) }}
                                    </div>
                                    <div class="text-end md:order-4 md:w-32">
                                        <a href="{{ route('user.order.show', $order->id) }}"
                                            class="cursor-pointer whitespace-nowrap rounded-md bg-neutral-50 px-4 py-2 text-sm font-medium tracking-wide text-neutral-900 transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-50 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-neutral-900 dark:text-white dark:focus-visible:outline-neutral-900">
                                            View
                                        </a>
                                    </div>
                                </div>
                                <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                    <div class="leading-none">
                                        <a href="{{ route('user.order.show', $order->id) }}"
                                            class="text-base font-medium text-black hover:underline leading-none">
                                            #{{ $order->hashed_id }}
                                        </a>
                                        <div class="text-sm text-gray-400 hover:!no-underline">
                                            {{ date('d M, Y', strtotime($order->created_at)) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="m-0 p-0 inline-flex">
                                            <button type="button" class="inline-flex items-center text-sm font-medium">
                                                <span
                                                    class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                                                    {{ $order->status_value }}
                                                </span>
                                            </button>
                                        </div>
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
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection
