@extends('errors.layout')

@section('title', 'Method Not Allowed')
@section('code', '405')
@section('message', 'The request method is not allowed for this resource. Please check your request and try again.')

@section('icon')
<div class="flex justify-center mb-6">
    <div class="relative">
        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center pulse-animation">
            <i class="fas fa-ban text-4xl text-indigo-500"></i>
        </div>
        <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
            <i class="fas fa-times text-sm text-white"></i>
        </div>
    </div>
</div>
@endsection
