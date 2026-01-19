@extends('frontend.layouts.app')
@section('title', 'Tips')
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
            Tips
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
                    Tips
                </span>
            </a>
            <div class="mx-auto w-full">
                {{-- @forelse ($orders as $order) --}}
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($blogs as $blog)
                        <x-blog :blog="$blog" />
                    @empty
                        <div class="text-center text-gray-400">
                            No data found...
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-3">
                {{ $blogs->links('pagination::tailwind') }}
            </div>
        </div>
        <!-- ./info -->
    </div>
    <!-- ./wrapper -->
@endsection
