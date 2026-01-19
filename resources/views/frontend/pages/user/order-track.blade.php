@extends('frontend.layouts.user')
@section('title', 'Track Order')
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
            Track Order
        </p>
    </div>
    <!-- ./breadcrumb -->

    <!-- wrapper -->
    <div class="container grid grid-cols-1 md:grid-cols-12 items-start gap-6 pt-4 pb-16">

        <!-- info -->
        <div class="col-span-full md:col-span-full shadow rounded px-6 pt-5 pb-7">
            <a href="{{ route('user.account.menu') }}" class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
                <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
                <span>
                    Track Order
                </span>
            </a>

            <!-- Order Search Form -->
            <div class="mx-auto w-full max-w-md mb-8">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Enter Order Details</h3>
                    <form method="GET" action="{{ route('user.order.track') }}" class="space-y-4">
                        <div>
                            <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Order ID or Order Number
                            </label>
                            <input type="text" 
                                   id="order_id" 
                                   name="order_id" 
                                   value="{{ old('order_id', $orderId) }}"
                                   placeholder="e.g., AB-123456789 or 123"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                Enter your order number (e.g., AB-123456789) or order ID
                            </p>
                        </div>
                        <button type="submit" 
                                class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-600 transition duration-200">
                            <i class="fa-solid fa-search mr-2"></i>
                            Track Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Tracking Results -->
            @if($orderId)
                @if($order)
                    <div class="mx-auto w-full">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <!-- Order Header -->
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">
                                            Order: <span id="user-order-id">{{ $order->hashed_id }}</span>
                                            <button class="btn btn-sm btn-light" id="copy-user-order-id-btn" title="Copy Order ID">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </h2>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Placed on {{ date('F d, Y', strtotime($order->created_at)) }}
                                        </p>
                                    </div>
                                    <div class="mt-4 md:mt-0">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($order->status == 1) bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 2) bg-blue-100 text-blue-800
                                            @elseif($order->status == 3) bg-purple-100 text-purple-800
                                            @elseif($order->status == 4) bg-indigo-100 text-indigo-800
                                            @elseif($order->status == 5) bg-green-100 text-green-800
                                            @elseif($order->status == 6) bg-red-100 text-red-800
                                            @elseif($order->status == 7) bg-gray-100 text-gray-800
                                            @elseif($order->status == 8) bg-orange-100 text-orange-800
                                            @endif">
                                            {{ $order->status_value }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Status Timeline -->
                            <div class="px-6 py-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                                <div class="space-y-4">
                                    @php
                                        $statuses = [
                                            1 => ['name' => 'Order Placed', 'description' => 'Your order has been received and is being processed'],
                                            2 => ['name' => 'Order Confirmed', 'description' => 'Your order has been confirmed by our team'],
                                            3 => ['name' => 'Packaging', 'description' => 'Your order is being prepared for shipment'],
                                            4 => ['name' => 'Out for Delivery', 'description' => 'Your order is on its way to you'],
                                            5 => ['name' => 'Delivered', 'description' => 'Your order has been successfully delivered'],
                                            6 => ['name' => 'Cancelled', 'description' => 'Your order has been cancelled'],
                                            7 => ['name' => 'Returned', 'description' => 'Your order has been returned'],
                                            8 => ['name' => 'Failed to Deliver', 'description' => 'Delivery attempt failed'],
                                        ];
                                    @endphp

                                    @for($i = 1; $i <= 8; $i++)
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($order->status >= $i)
                                                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                                                        <i class="fa-solid fa-check text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                        <span class="text-gray-400 text-sm">{{ $i }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium 
                                                    @if($order->status >= $i) text-gray-900 @else text-gray-500 @endif">
                                                    {{ $statuses[$i]['name'] }}
                                                </p>
                                                <p class="text-xs 
                                                    @if($order->status >= $i) text-gray-600 @else text-gray-400 @endif">
                                                    {{ $statuses[$i]['description'] }}
                                                </p>
                                                @if($order->status == $i && $order->status < 6)
                                                    <p class="text-xs text-primary font-medium mt-1">
                                                        Current Status
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Order Not Found -->
                    <div class="mx-auto w-full max-w-md">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Order Not Found</h3>
                            <p class="text-red-700 mb-4">
                                We couldn't find an order with the ID "{{ $orderId }}". Please check your order number and try again.
                            </p>
                            <div class="text-sm text-red-600">
                                <p class="mb-2">Make sure you entered:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>The correct order number (e.g., AB-123456789)</li>
                                    <li>The order belongs to your account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Initial State -->
                <div class="mx-auto w-full max-w-md">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-truck text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Track Your Order</h3>
                        <p class="text-blue-700 mb-4">
                            Enter your order number above to track the status of your order and see real-time updates.
                        </p>
                        <div class="text-sm text-blue-600">
                            <p class="mb-2">You can find your order number:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>In your order confirmation email</li>
                                <li>In your account's order history</li>
                                <li>On your invoice or receipt</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection

@push('scripts')
<script>
    const btn = document.getElementById('copy-user-order-id-btn');
    if (btn) {
        btn.addEventListener('click', function () {
            const text = document.getElementById('user-order-id').innerText;
            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fa-solid fa-copy"></i>';
                }, 1500);
            });
        });
    }
</script>
@endpush
