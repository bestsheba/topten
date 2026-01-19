@extends('frontend.layouts.app')
@section('title', 'Product')
@section('content')
    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">{{ $page->title }}</p>
    </div>

    <div class="container">
        <section class="mb-8">
            <h2 class="text-xl text-center font-semibold mb-4">{{ $page->title }}</h2>
            <div class="text-gray-600 leading-relaxed prose">
                <div class="bg-gray-100 rounded">
                    <div class="mx-auto px-8 !py-[20px]">
                        {!! $page->description !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .table {
            width: 100% !important;
        }

        table {
            width: 100% !important;
        }

        .prose {
            max-width: 100%;
        }

        .prose :where(figure):not(:where([class~="not-prose"], [class~="not-prose"] *)) {
            margin-top: 0em;
        }
    </style>
@endpush
