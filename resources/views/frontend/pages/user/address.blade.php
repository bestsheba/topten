@extends('frontend.layouts.app')
@section('title', 'Address')
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
            Address
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
                    Address
                </span>
            </a>
            <div class="mx-auto w-full">
                <div class="border w-full max-w-full px-3 lg-max:mt-6 xl:w-4/12 mb-4 shadow-sm rounded">
                    <div
                        class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                                    <h6 class="mb-0">
                                        Your Address
                                    </h6>
                                </div>
                                <div class="w-full max-w-full px-3 text-right shrink-0 md:w-4/12 md:flex-none">
                                    <a href="#form">
                                        <i class="text-2xl las la-user-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4">
                            <p class="leading-normal text-sm">
                                {{ $address?->address ?? 'N/A' }}
                            </p>
                            <hr
                                class="h-px my-6 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li
                                    class="relative block px-4 py-2 pt-0 pl-0 leading-normal bg-white border-0 rounded-t-lg text-sm text-inherit">
                                    <strong class="text-slate-700">
                                        Name:
                                    </strong> &nbsp; {{ $address?->name ?? 'N/A' }}
                                </li>
                                <li
                                    class="relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-sm text-inherit">
                                    <strong class="text-slate-700">
                                        Mobile:
                                    </strong> &nbsp; {{ $address?->phone_number ?? 'N/A' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-2" id="form">
            <!-- form  -->
            <h4 class="text-lg font-medium capitalize mb-4">
                Change
            </h4>
            <form action="{{ route('user.address') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div>
                            <label for="name" class="text-gray-600 mb-2 block">
                                Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $address?->name) }}"
                                class="@error('name') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                placeholder="Name">
                            @error('name')
                                <span class="text-red-500">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="text-gray-600 mb-2 block">
                                Phone Number
                            </label>
                            <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', $address?->phone_number) }}"
                                class="@error('phone_number') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
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
                                class="@error('address') border-red-500 @enderror block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                placeholder="Address">{{ old('address', $address?->address) }}</textarea>
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
        <!-- ./info -->
    </div>
    <!-- ./wrapper -->
@endsection
