@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('message', 'We\'re temporarily down for maintenance. We\'ll be back online shortly. Thank you for your patience.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-wrench text-4xl text-yellow-600"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
            <i class="fas fa-cog fa-spin text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
