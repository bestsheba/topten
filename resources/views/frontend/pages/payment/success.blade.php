@extends('frontend.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <div class="mb-6">
            {{-- Using FontAwesome icon if available in the project --}}
            <i class="fas fa-check-circle text-green-500" style="font-size: 4rem;"></i>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Payment Successful!</h2>
        <p class="text-gray-600 mb-6">Your order has been successfully placed and payment has been received.</p>

        <div class="space-y-2 text-left max-w-md mx-auto mb-6">
            <p class="text-gray-700"><span class="font-medium">Order ID:</span> #{{ $order->id }}</p>
            <p class="text-gray-700"><span class="font-medium">Total Amount:</span> ৳{{ number_format($order->total, 2) }}</p>
            <p class="text-gray-700"><span class="font-medium">Payment Method:</span> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
        </div>

        <div>
            <a href="{{ route('index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection