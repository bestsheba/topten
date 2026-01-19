@extends('frontend.layouts.app')
@section('title', 'Register')
@section('content')
    <!-- Register -->
    <div class="contain py-16">
        <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
            <h2 class="text-2xl uppercase font-medium mb-1">Create an account</h2>
            <p class="text-gray-600 mb-6 text-sm">
                Register for new cosutumer
            </p>
            <form action="{{ route('register') }}" method="post" autocomplete="off">
                @csrf
                <div class="space-y-2">
                    <div>
                        <label for="name" class="text-gray-600 mb-2 block">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            @error('name') is-invalid @enderror"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="fulan fulana">
                        @error('name')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
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
                    <div>
                        <label for="phone" class="text-gray-600 mb-2 block">Phone number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            @error('phone') is-invalid @enderror"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="01XXXXXXXXX">
                        @error('phone')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="address" class="text-gray-600 mb-2 block">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="House, road, area, city">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="text-gray-600 mb-2 block">Password</label>
                        <input type="password" name="password" id="password"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="*******">
                        @error('password')
                            <span class="text-red-500">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="confirm" class="text-gray-600 mb-2 block">Confirm password</label>
                        <input type="password" name="password_confirmation" id="confirm"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                            placeholder="*******">
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="aggrement" id="aggrement" required
                            class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                        <label for="aggrement" class="text-gray-600 ml-3 cursor-pointer">I have read and agree to the <a
                                href="#" class="!text-primary">terms & conditions</a></label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="block w-full py-2 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition uppercase font-roboto font-medium">create
                        account</button>
                </div>
            </form>

            <!-- login with -->
            @if($settings && $settings->google_login_enabled)
                <div class="mt-6 flex justify-center relative">
                    <div class="text-gray-600 uppercase px-3 bg-white z-10 relative">Or signup with</div>
                    <div class="absolute left-0 top-3 w-full border-b-2 border-gray-200"></div>
                </div>
                <div class="mt-4 flex gap-4">
                    <a href="{{ route('google.redirect') }}"
                        class="px-4 w-full py-2 border flex justify-center items-center gap-2 00 rounded text-center  border-slate-400 late-500 text-slate-900 te-300 shadow hover:shadow-none transition duration-150">
                        <img class="w-6 h-6" src="https://www.svgrepo.com/show/475656/google-color.svg" loading="lazy"
                            alt="google logo">
                        <span>Register with Google</span>
                    </a>
                </div>
            @endif
            <!-- ./login with -->

            <p class="mt-4 text-center text-gray-600">Already have account? <a href="{{ route('login') }}"
                    class="!text-primary">Login now</a></p>
        </div>
    </div>
    <!-- ./Register -->
@endsection
