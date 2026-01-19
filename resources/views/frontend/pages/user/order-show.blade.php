@extends('frontend.layouts.app')
@section('title', 'Order #' . $order->hashed_id)
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
            Order <span id="user-show-order-id-1">{{ $order->hashed_id }}</span>
            <button class="btn btn-sm btn-light" id="copy-user-show-order-id-btn-1" title="Copy Order ID"><i class="fa-solid fa-copy"></i></button>
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
            <a href="{{ route('user.account.menu') }}"
                class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
                <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
                <span>
                    Order {{ $order->hashed_id }}
                </span>
            </a>
            <div class="mx-auto w-full">
                <div class="mt-6 sm:mt-8 md:gap-6 grid grid-cols-1 md:grid-cols-2">
                    <div class="mx-auto w-full flex-none">
                        @foreach ($order->items as $item)
                            <div class="mb-2">
                                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:p-6">
                                    <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                        <a href="{{ route('product.details', $item->product->slug) }}"
                                            class="shrink-0 md:order-1">
                                            <img class="h-20 w-20" src="{{ $item->product->picture_url }}"
                                                alt="{{ $item->product->name }}" />
                                        </a>
                                        <div class="flex items-center justify-between md:order-3 md:justify-end">
                                            <div class="flex items-center">
                                                <input type="text"
                                                    class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-black focus:outline-none focus:ring-0"
                                                    placeholder="" value="{{ $item->quantity }}" required readonly>
                                            </div>
                                            <div class="text-end md:order-4 md:w-32">
                                                <p class="text-base font-bold text-black">
                                                    {{ showAmount($item->product->final_price * $item->quantity) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                            <div class="leading-none">
                                                <a href="{{ route('product.details', $item->product->slug) }}"
                                                    class="text-base font-medium text-black hover:underline leading-none">
                                                    {{ $item->product->name }}
                                                </a>
                                                <div class="text-sm text-gray-400 hover:!no-underline">
                                                    {{ showAmount($item->product->final_price) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mx-auto mt-6 flex-1 space-y-6 lg:mt-0 w-full">
                        <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                            <p class="text-xl font-semibold text-gray-900">
                                Order summary
                            </p>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-700">
                                            Number
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900">
                                            #<span id="user-show-order-id-2">{{ $order->hashed_id }}</span>
                                            <button class="btn btn-sm btn-light" id="copy-user-show-order-id-btn-2" title="Copy Order ID"><i class="fa-solid fa-copy"></i></button>
                                        </dd>
                                    </dl>
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-700">
                                            Status
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900">
                                            {{ $order->status_value }}
                                        </dd>
                                    </dl>
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-700">
                                            Sub Total
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900">
                                            {{ showAmount($order->total) }}
                                        </dd>
                                    </dl>
                                </div>
                                <dl class="flex items-center justify-between gap-4 border-t border-gray-400 pt-2">
                                    <dt class="text-base font-bold text-gray-900">
                                        Total
                                    </dt>
                                    <dd class="text-base font-bold text-gray-900">
                                        {{ showAmount($order->total) }}
                                    </dd>
                                </dl>
                            </div>
                            <a href="{{ route('admin.invoice.download', $order->id) }}"
                                class="flex w-full items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-400">
                                Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection

@push('scripts')
<script>
    const btn1 = document.getElementById('copy-user-show-order-id-btn-1');
    if (btn1) {
        btn1.addEventListener('click', function () {
            const text = document.getElementById('user-show-order-id-1').innerText;
            navigator.clipboard.writeText(text).then(function () {
                btn1.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => { btn1.innerHTML = '<i class=\'fa-solid fa-copy\'></i>'; }, 1500);
            });
        });
    }
    const btn2 = document.getElementById('copy-user-show-order-id-btn-2');
    if (btn2) {
        btn2.addEventListener('click', function () {
            const text = document.getElementById('user-show-order-id-2').innerText;
            navigator.clipboard.writeText(text).then(function () {
                btn2.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => { btn2.innerHTML = '<i class=\'fa-solid fa-copy\'></i>'; }, 1500);
            });
        });
    }
</script>
@endpush
