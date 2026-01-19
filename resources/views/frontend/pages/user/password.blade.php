@extends('frontend.layouts.user')
@section('title', 'My Account')
@section('content')
    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">
            Profile
        </p>
    </div>
    <!-- ./breadcrumb -->

    <!-- wrapper -->
    <div class="container grid grid-cols-1 md:grid-cols-12 items-start gap-6 pt-4 pb-16">

        <!-- sidebar -->
        {{-- @include('frontend.pages.user.sidebar') --}}
        <!-- ./sidebar -->

        <!-- info -->
        <div class="col-span-full md:col-span-full shadow rounded px-6 pt-5 pb-7">
            <a href="{{ route('user.account.menu') }}" class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
                <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
                <span>
                    Password
                </span>
            </a>
            <form action="{{ route('user.password.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div>
                            <label for="old_password" class="text-gray-600 mb-2 block">
                                Old Password
                            </label>
                            <input type="password" name="old_password" id="old_password" value=""
                                class="@error('old_password') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                placeholder="Old Password">
                            @error('old_password')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password" class="text-gray-600 mb-2 block">
                                New Password
                            </label>
                            <input type="password" name="new_password" id="new_password" value=""
                                class="@error('new_password') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                placeholder="Old Password">
                            @error('new_password')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="confirm_password" class="text-gray-600 mb-2 block">
                                Confirm Password
                            </label>
                            <input type="password" name="confirmed_password" id="confirm_password" value=""
                                class="@error('confirm_password') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                placeholder="Old Password">
                            @error('confirm_password')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection
