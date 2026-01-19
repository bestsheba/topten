@extends('errors.layout')

@section('title', 'Access Forbidden')
@section('code', '403')
@section('message', 'You don\'t have permission to access this page. Contact your administrator if you believe this is an error.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-ban text-4xl text-red-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
            <i class="fas fa-times text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
