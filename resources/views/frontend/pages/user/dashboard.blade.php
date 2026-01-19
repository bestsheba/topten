@extends('frontend.layouts.user')
@section('title', 'Dashboard')
@push('styles')
<style>
</style>
@endpush
@section('content')
    <section class="px-4 mt-3 py-8">
        <a href="{{ route('user.account.menu') }}" class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
            <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
            <span>
                Dashboard
            </span>
        </a>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="shadow bg-white rounded-lg p-4 relative overflow-hidden">
                <!-- Curved top design -->
                <div class="absolute -top-[25px] left-0 w-full h-[50px] bg-green-500 curve"></div>

                <!-- Content inside the card -->
                <p class="text-sm text-gray-600 mt-8">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{$order_count}}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-4 relative overflow-hidden">
                <!-- Curved top design -->
                <div class="absolute -top-[25px] left-0 w-full h-[50px] bg-orange-500 curve"></div>

                <!-- Content inside the card -->
                <p class="text-sm text-gray-600 mt-8">Pending</p>
                <p class="text-2xl font-bold text-gray-800">{{$pending_order_count}}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-4 relative overflow-hidden">
                <!-- Curved top design -->
                <div class="absolute -top-[25px] left-0 w-full h-[50px] bg-emerald-500 curve"></div>

                <!-- Content inside the card -->
                <p class="text-sm text-gray-600 mt-8">Total Spent</p>
                <p class="text-2xl font-bold text-gray-800">{{showAmount($total_spent)}}</p>
            </div>
        </div>
    </section>
@endsection
