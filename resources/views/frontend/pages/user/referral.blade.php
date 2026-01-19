@extends('frontend.layouts.app')
@section('title', 'Referral')
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
            Referral
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
                    Referral
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
                                        Your Referral Link
                                    </h6>
                                </div>
                                <div class="w-full max-w-full px-3 text-right shrink-0 md:w-4/12 md:flex-none">
                                    <a href="">
                                        <i class="text-2xl las la-copy"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4">
                            <p class="leading-normal text-sm">
                                Https//www.example.com/demo-referral-link
                            </p>
                        </div>
                    </div>
                </div>


                {{-- @forelse ($orders as $order) --}}
                <div class="flex-auto p-4">
                    <h4 class="text-lg font-medium capitalize mb-4">
                        User List
                    </h4>
                    <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                        @for ($i = 0; $i < 12; $i++)
                            <li
                                class="relative flex items-center px-0 py-2 bg-white text-inherit {{ $i == 0 ? '' : 'border-t' }}">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 mr-4 text-white transition-all duration-200 text-base ease-soft-in-out rounded-xl">
                                    <img src="https://demos.creative-tim.com/soft-ui-dashboard-tailwind/assets/img/team-4.jpg"
                                        alt="kal" class="w-full shadow-soft-2xl rounded-xl">
                                </div>
                                <div class="flex flex-col items-start justify-center">
                                    <h6 class="mb-0 leading-normal text-sm">Sophie B.</h6>
                                    <p class="mb-0 leading-tight text-xs">Hi! I need more information..</p>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
                {{-- @empty
                    <div class="text-center text-gray-400">
                        No data found...
                    </div>
                @endforelse --}}
            </div>
        </div>
        <!-- ./info -->
    </div>
    <!-- ./wrapper -->
@endsection
