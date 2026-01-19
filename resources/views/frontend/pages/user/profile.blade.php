@extends('frontend.layouts.user')
@section('title', 'My Account')
@section('content')
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">Profile</p>
    </div>
    <div class="container grid grid-cols-1 md:grid-cols-12 items-start gap-6 pt-4 pb-16">
        <div class="col-span-full md:col-span-full shadow rounded px-6 pt-5 pb-7">
            <a href="{{ route('user.account.menu') }}" class="md:hidden text-lg font-medium capitalize mb-4 flex items-center gap-2">
                <i class="las la-arrow-circle-left !text-2xl !font-bold"></i>
                <span>
                    Profile information
                </span>
            </a>
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div>
                            <label for="name" class="text-gray-600 mb-2 block">
                                Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400 @error('name') is-invalid @enderror"
                                placeholder="Name">
                            @error('name')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="avatar" class="text-gray-600 mb-2 block">
                                Avatar
                            </label>
                            <input type="file" accept="image/*" name="avatar" id="avatar"
                                class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400 @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="text-gray-600 mb-2 block">
                                Phone
                            </label>
                            <input type="text" name="phone_number" id="phone" value="{{ old('phone_number', $user?->address?->phone_number) }}"
                                class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400 @error('phone_number') is-invalid @enderror"
                                placeholder="Phone Number">
                            @error('phone_number')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="address" class="text-gray-600 mb-2 block">
                                Address
                            </label>
                            <textarea name="address" id="address"
                            class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400 @error('address') is-invalid @enderror"
                            placeholder="Your Address">{{ old('address', $user?->address?->address) }}</textarea>
                            @error('address')
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
    </div>
@endsection
