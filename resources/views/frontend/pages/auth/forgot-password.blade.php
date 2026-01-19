@extends('frontend.layouts.app')
@section('title', 'Forgot Password')
@section('content')
    <div class="contain py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <h2 class="text-2xl uppercase font-medium mb-1">Forgot Password</h2>
            <p class="text-gray-600 mb-6 text-sm">
                Recover you password now
            </p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="space-y-2">
                    <div>
                        <label for="email" class="text-gray-600 mb-2 block">Email address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            @error('email') is-invalid @enderror"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="youremail.@domain.com">
                        @error('email')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="block w-full py-2 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition uppercase font-roboto font-medium">{{__('Email Password Reset Link')}}</button>
                </div>
            </form>

            <p class="mt-4 text-center text-gray-600">Remember Password? Go back to <a href="{{route('login')}}"
                    class="!text-primary">Login</a></p>
        </div>
    </div>
@endsection
