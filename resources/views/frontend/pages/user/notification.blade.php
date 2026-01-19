@extends('frontend.layouts.app')
@section('title', 'Notification')
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
            Notification
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
                    Notification
                </span>
            </a>
            <div class="mx-auto w-full">
                @forelse ($notifications as $notification)
                    <div class="w-full p-3 mt-1 bg-white rounded flex border border-gray-100">
                        <div tabindex="0" aria-label="heart icon" role="img"
                            class="focus:outline-none w-8 h-8 border rounded-full border-gray-200 flex items-center justify-center">
                            <i class="text-2xl las la-bell text-red-500"></i>
                        </div>
                        <div class="pl-3">
                            <p tabindex="0" class="focus:outline-none text-sm leading-none">
                                <span class="text-indigo-500">
                                    {{ $notification->data['title'] }}
                                </span>
                            </p>
                            <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400">
                        No data found...
                    </div>
                @endforelse
            </div>
            <div class="{{$notifications->hasPages() ? 'mt-3' : ''}}">
                {{ $notifications->links('pagination::tailwind') }}
            </div>
        </div>
        <!-- ./info -->

    </div>
    <!-- ./wrapper -->
@endsection
