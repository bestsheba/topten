@extends('frontend.layouts.front')
@section('title', 'OTP Verification')
@section('content')
    <div class="contain py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <h2 class="text-2xl uppercase font-medium mb-1">OTP Verification</h2>
            <p class="text-gray-600 mb-6 text-sm">
                Please verify your email to continue
            </p>
            <form id="otpForm" method="POST" action="{{ route('otp.verify.submit') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div class="space-y-2">
                    <div>
                        <label for="otp" class="text-gray-600 mb-2 block">Enter 6-digit OTP</label>
                        <input type="text" name="otp" id="otp"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="Enter 6-digit OTP" required>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="resend()" class="text-primary hover:underline">
                        Resend OTP
                    </button>
                    <a href="{{ route('logout') }}" 
                       class="text-red-600 hover:underline text-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="block w-full py-2 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition uppercase font-roboto font-medium">
                        Verify OTP
                    </button>
                </div>
            </form>
        </div>
        <form action="{{ route('otp.resend') }}" method="POST" id="resend">
            @csrf
        </form>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
            @csrf
        </form>
    </div>
@endsection
<script>
    function resend() {
        document.getElementById('resend').submit();
    }
</script>