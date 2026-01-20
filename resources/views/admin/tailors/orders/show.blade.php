@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('page')
    <div class="container py-4">

        <div class="mb-3">
            <a href="{{ route('admin.tailor.orders') }}" class="btn btn-outline-secondary">
                ← Back to Orders
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Order Details
                </h5>
            </div>

            <div class="card-body">

                {{-- Basic Info --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Customer:</strong> {{ $order->customer?->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tailor:</strong> {{ $order->tailor?->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Garment:</strong> {{ $order->garmentType?->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Price:</strong> {{ number_format($order->price, 2) }}
                    </div>
                   
                </div>

                {{-- Measurements --}}
                <h6 class="fw-bold mb-3">Measurements</h6>

                <div class="row">
                    @foreach ($order->measurements as $key => $value)
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2">
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong><br>
                                <span>{{ $value }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
