@extends('frontend.layouts.user')
@section('title', 'Transaction')
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
            Transaction
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
                    Transaction
                </span>
            </a>
            <div class="mx-auto w-full">
                {{-- @forelse ($orders as $order) --}}
                <table class="min-w-full border">
                    <thead class="hidden border-b lg:table-header-group">
                        <tr class="">
                            <td width="50%" class="whitespace-normal py-4 text-sm font-medium text-gray-500 sm:px-6">
                                Order
                            </td>
                            <td class="whitespace-normal py-4 text-sm font-medium text-gray-500 sm:px-6">
                                Date
                            </td>
                            <td class="whitespace-normal py-4 text-sm font-medium text-gray-500 sm:px-6">
                                Amount
                            </td>
                            <td class="whitespace-normal py-4 text-sm font-medium text-gray-500 sm:px-6">
                                Status
                            </td>
                        </tr>
                    </thead>
                    <tbody class="lg:border-gray-300 border">
                        <tr class="border-b">
                            <td width="50%" class="whitespace-no-wrap px-4 py-4 text-sm font-bold text-gray-900 sm:px-6">
                                Standard Plan - Feb 2022
                                <div class="mt-1 lg:hidden">
                                    <p class="font-normal text-gray-500">
                                        07 February, 2022
                                    </p>
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                07 February, 2022
                            </td>
                            <td class="whitespace-no-wrap py-4 px-6 text-right text-sm text-gray-600 lg:text-left">
                                $59.00
                                <div
                                    class="flex mt-1 ml-auto w-fit items-center rounded-full bg-blue-600 py-2 px-3 text-left text-xs font-medium text-white lg:hidden">
                                    Complete
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                <div class="inline-flex items-center rounded-full bg-blue-600 py-2 px-3 text-xs text-white">
                                    Complete
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td width="50%" class="whitespace-no-wrap px-4 py-4 text-sm font-bold text-gray-900 sm:px-6">
                                Standard Plan - Jan 2022
                                <div class="mt-1 lg:hidden">
                                    <p class="font-normal text-gray-500">
                                        09 January, 2022
                                    </p>
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                09 January, 2022
                            </td>
                            <td class="whitespace-no-wrap py-4 px-6 text-right text-sm text-gray-600 lg:text-left">
                                $59.00
                                <div
                                    class="flex mt-1 ml-auto w-fit items-center rounded-full bg-red-200 py-1 px-2 text-left font-medium text-red-500 lg:hidden">
                                    Canceled
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                <div class="inline-flex items-center rounded-full bg-red-200 py-1 px-2 text-red-500">
                                    Canceled
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td width="50%" class="whitespace-no-wrap px-4 py-4 text-sm font-bold text-gray-900 sm:px-6">
                                Basic Plan - Dec 2021
                                <div class="mt-1 lg:hidden">
                                    <p class="font-normal text-gray-500">
                                        15 December, 2021
                                    </p>
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                15 December, 2021
                            </td>
                            <td class="whitespace-no-wrap py-4 px-6 text-right text-sm text-gray-600 lg:text-left">
                                $29.00
                                <div
                                    class="flex mt-1 ml-auto w-fit items-center rounded-full bg-blue-600 py-2 px-3 text-left text-xs font-medium text-white lg:hidden">
                                    Complete
                                </div>
                            </td>

                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                <div class="inline-flex items-center rounded-full bg-blue-600 py-2 px-3 text-xs text-white">
                                    Complete
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td width="50%" class="whitespace-no-wrap px-4 py-4 text-sm font-bold text-gray-900 sm:px-6">
                                Basic Plan - Nov 2021
                                <div class="mt-1 lg:hidden">
                                    <p class="font-normal text-gray-500">
                                        14 November, 2021
                                    </p>
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                14 November, 2021
                            </td>
                            <td class="whitespace-no-wrap py-4 px-6 text-right text-sm text-gray-600 lg:text-left">
                                $29.00
                                <div
                                    class="flex mt-1 ml-auto w-fit items-center rounded-full bg-blue-200 py-1 px-2 text-left font-medium text-blue-500 lg:hidden">
                                    Pending
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                <div class="inline-flex items-center rounded-full bg-blue-200 py-1 px-2 text-blue-500">
                                    Pending
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td width="50%" class="whitespace-no-wrap px-4 py-4 text-sm font-bold text-gray-900 sm:px-6">
                                Basic Plan - Oct 2021
                                <div class="mt-1 lg:hidden">
                                    <p class="font-normal text-gray-500">
                                        15 October, 2021
                                    </p>
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                15 October, 2021
                            </td>
                            <td class="whitespace-no-wrap py-4 px-6 text-right text-sm text-gray-600 lg:text-left">
                                $29.00
                                <div
                                    class="flex mt-1 ml-auto w-fit items-center rounded-full bg-blue-600 py-2 px-3 text-left text-xs font-medium text-white lg:hidden">
                                    Complete
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap hidden py-4 text-sm font-normal text-gray-500 sm:px-6 lg:table-cell">
                                <div class="inline-flex items-center rounded-full bg-blue-600 py-2 px-3 text-xs text-white">
                                    Complete
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
