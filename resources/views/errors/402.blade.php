@extends('errors.layout')

@section('title', 'Payment Required')
@section('code', '402')
@section('message', 'This resource requires payment to access. Please complete your payment to continue.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-credit-card text-4xl text-yellow-600"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
            <i class="fas fa-dollar-sign text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
