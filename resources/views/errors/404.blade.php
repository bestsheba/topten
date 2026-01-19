@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message', 'The page you\'re looking for doesn\'t exist or has been moved. Let\'s get you back on track!')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-search text-4xl text-blue-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
            <i class="fas fa-question text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
