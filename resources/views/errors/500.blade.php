@extends('errors.layout')

@section('title', 'Internal Server Error')
@section('code', '500')
@section('message', 'Something went wrong on our end. We\'re working to fix this issue. Please try again later.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-server text-4xl text-red-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
            <i class="fas fa-tools text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
