@extends('admin.layouts.app')
@section('title')
    Create Incomplete Order
@endsection
@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Create Incomplete Order',
        'page' => 'Create Incomplete Order',
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
                                Create Incomplete Order
                            </h3>
                        </div>
                        <form action="{{ route('admin.order.incomplete.place-order', $inc_order->id) }}" method="POST"
                            id="form">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="scroll_height" id="scroll_height" value="" readonly>
                                <div class="form-group">
                                    <label for="name">
                                        Name
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        placeholder="Enter Name" value="{{ old('name', $inc_order->name) }}">
                                    @error('name')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone_number"
                                        class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                                        placeholder="Enter Phone Number"
                                        value="{{ old('phone_number', $inc_order->phone_number) }}">
                                    @error('phone_number')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">
                                        Address
                                    </label>
                                    <textarea rows="1" name="address" class="form-control @error('address') is-invalid @enderror" id="address"
                                        placeholder="Enter Address">{{ old('address', $inc_order->address) }}</textarea>
                                    @error('address')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="delivery_charge">
                                        Delivery Charge
                                    </label>
                                    <input type="number" step="any" name="delivery_charge"
                                        class="form-control @error('delivery_charge') is-invalid @enderror"
                                        id="delivery_charge" placeholder="Enter Delivery Charge"
                                        value="{{ old('delivery_charge', 0) }}">
                                    @error('delivery_charge')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
@endsection
@section('script')
@endsection
