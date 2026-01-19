@extends('admin.layouts.app')

@section('title')
    Add Coupon
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Coupon',
        'page' => 'Add Coupon',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Add Coupon
                            </h3>
                        </div>
                        <form action="{{ route('admin.coupon.store') }}" method="POST" id="form" class="p-4"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                @error('title')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                                @error('code')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date', date('Y-m-d', strtotime(now()))) }}">
                                @error('start_date')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="expired_date">Expired Date</label>
                                <input type="date" name="expired_date"
                                    class="form-control @error('expired_date') is-invalid @enderror"
                                    value="{{ old('expired_date', date('Y-m-d', strtotime(now()))) }}">
                                @error('expired_date')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" step="0.01" min="0"
                                    class="form-control @error('discount') is-invalid @enderror" 
                                    value="{{ old('discount') }}" placeholder="Enter discount amount">
                                @error('discount')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="discount_type">Discount Type</label>
                                <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
                                    <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Amount</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                                @error('discount_type')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
