@extends('errors.layout')

@section('title', 'Page Expired')
@section('code', '419')
@section('message', 'Your session has expired due to inactivity. Please refresh the page and try again.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-clock text-4xl text-orange-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
