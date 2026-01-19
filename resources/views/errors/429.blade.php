@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429')
@section('message', 'You\'ve made too many requests. Please wait a moment before trying again.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-tachometer-alt text-4xl text-purple-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
            <i class="fas fa-hourglass-half text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
