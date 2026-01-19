@extends('frontend.layouts.app')
@section('title', 'Register')
@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-semibold mb-6 px-2 lg:px-2">Affiliate Registration</h1>
    <form action="{{ route('affiliate.register.post') }}" method="POST" class="space-y-4 bg-white p-6 rounded border">
        @csrf
        @guest
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2" placeholder="Your full name">
                @error('name')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" placeholder="you@example.com">
                @error('email')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" placeholder="********">
                @error('password')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" placeholder="********">
            </div>
        </div>
        @else
        <p>Logged in as <strong>{{ auth()->user()->email }}</strong></p>
        @endguest

        <p class="text-sm">Join our affiliate program and earn commission on sales from your referral link.</p>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="agree" value="1" class="h-4 w-4">
            <span>I agree to the affiliate terms.</span>
        </label>
        @error('agree')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
        <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded hover:bg-primary/70 transition duration-300">Join Now</button>
    </form>
    
    <!-- Login and Password Reset Links -->
    <div class="mt-6 text-center space-y-2">
        <p class="text-gray-600">Already have an account? 
            <a href="{{ route('login', ['from_affiliate' => 1]) }}" class="text-primary hover:underline">Login here</a>
        </p>
        @if (Route::has('password.request'))
            <p class="text-gray-600">Forgot your password? 
                <a href="{{ route('password.request') }}" class="text-primary hover:underline">Reset password</a>
            </p>
        @endif
    </div>
</div>
@endsection


