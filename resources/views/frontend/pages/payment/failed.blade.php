@extends('frontend.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-times-circle text-red-500" style="font-size: 4rem;"></i>
        </div>

        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Payment Failed</h2>
        <p class="text-gray-600 mb-6">{{ $message ?? 'Your payment could not be processed. The order has been canceled.' }}</p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('cart') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">Return to Cart</a>
            <a href="{{ route('index') }}" class="inline-block border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium py-2 px-4 rounded-lg">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection