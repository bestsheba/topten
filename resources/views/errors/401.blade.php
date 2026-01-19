@extends('errors.layout')

@section('title', 'Unauthorized Access')
@section('code', '401')
@section('message', 'You don\'t have permission to access this resource. Please log in with proper credentials.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-lock text-4xl text-red-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation text-sm text-yellow-800"></i>
        </div>
    </div>
</div>
@endsection
