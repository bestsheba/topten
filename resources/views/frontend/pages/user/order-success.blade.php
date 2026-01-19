@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl relative">
            <!-- Close Button -->
            <a href="{{ route('index') }}" class="absolute -top-1  bg-red-500 rounded-full p-1 -right-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            <!-- Success Header -->
            <div class="bg-green-600 rounded-t-3xl text-white py-6 md:py-8 text-center">
                <div 
                    class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-green-500 bg-opacity-20 rounded-full mb-4 md:mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h1 class="text-2xl md:text-4xl font-black mb-2 md:mb-4 tracking-tight">Order Confirmed!</h1>
                <p class="text-base md:text-xl font-medium opacity-90 px-4">Your purchase is being processed with care</p>
            </div>

            <!-- Order Details Container -->
            <div class="p-4 md:p-6 space-y-4 md:space-y-6">
                <!-- Order ID Section -->
                <div class="bg-gray-100 rounded-xl p-4 md:p-5 text-center">
                    <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-2 uppercase tracking-wider font-semibold">Your Order ID</p>
                    <p class="text-xl md:text-3xl font-black text-gray-800 tracking-wider">{{ $order->hashed_id }}</p>
                </div>

                <!-- Order Summary Grid -->
                <div class="grid grid-cols-2 gap-3 md:gap-4">
                    <div class="bg-green-50 rounded-xl p-3 md:p-4 text-center">
                        <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-2 uppercase tracking-wider font-semibold">Total Amount</p>
                        <p class="text-base md:text-2xl font-black text-green-700">{{ showAmount($order->total) }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-3 md:p-4 text-center">
                        <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-2 uppercase tracking-wider font-semibold">Payment Method</p>
                        <p class="text-base md:text-2xl font-black text-blue-700">
                            @switch($order->payment_method)
                                @case('cash_on_delivery')
                                    Cash on Delivery
                                    @break
                                @case('online_payment')
                                    Online Payment
                                    @break
                                @default
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            @endswitch
                        </p>
                    </div>
                </div>

                <!-- Tracking URL -->
                <div class="bg-gray-100 rounded-xl p-4 md:p-5">
                    <p class="text-xs md:text-sm text-gray-600 mb-2 uppercase tracking-wider font-semibold text-center">Track Your Order</p>
                    <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                        <input type="text" 
                               value="{{ $order->hashed_id }}" 
                               readonly 
                               class="w-full px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               id="trackingUrlInput">
                        <button onclick="copyTrackingUrl()" 
                                class="w-full md:w-auto px-3 py-2 md:px-4 md:py-3 bg-green-600 text-white text-xs md:text-sm rounded-lg hover:bg-green-700 transition-colors mt-2 md:mt-0">
                            Copy
                        </button>
                    </div>
                </div>

                <!-- Ordered Items -->
                <div class="space-y-3 md:space-y-4 max-h-48 md:max-h-64 overflow-y-auto">
                    @foreach($order->items as $item)
                    <div class="bg-gray-100 rounded-xl p-3 md:p-4 flex items-center space-x-3 md:space-x-4">
                        @if($item->product && $item->product->thumbnail)
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-sm md:text-lg text-gray-800">
                                {{ $item->product->name ?? 'Product Not Found' }}</h4>
                            <p class="text-xs md:text-sm text-gray-600">
                                Qty: {{ $item->quantity }} | Price: {{ showAmount($item->price) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-sm md:text-xl text-gray-800">
                                 {{ showAmount($item->total) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                    <a href="{{ route('track.order', ['order_id' => $order->hashed_id]) }}"
                        class="w-full md:flex-1 px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors text-center font-bold text-sm md:text-base">
                        Track Order
                    </a>
                    <a href="{{ route('index') }}"
                        class="w-full md:flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors text-center font-bold text-sm md:text-base">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyTrackingUrl() {
    const input = document.getElementById('trackingUrlInput');
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        alert('Tracking Id copied!');
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}
</script>
@endsection
