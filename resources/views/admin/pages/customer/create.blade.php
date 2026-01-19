@extends('admin.layouts.app')

@section('title')
    Add Customer
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Customer',
        'page' => 'Add Customer',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Add New Customer
                            </h3>
                        </div>
                        <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">
                                                Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Customer Name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Enter Email Address" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">
                                                Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                placeholder="Enter Password">
                                            @error('password')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">
                                                Confirm Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" placeholder="Confirm Password">
                                            @error('password_confirmation')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="phone_number"
                                        class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                                        placeholder="Enter Phone Number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">
                                        Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3"
                                        placeholder="Enter Full Address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="avatar">
                                        Profile Picture (Optional)
                                    </label>
                                    <input type="file" name="avatar"
                                        class="form-control @error('avatar') is-invalid @enderror" style="line-height: 1.2"
                                        accept="image/*">
                                    @error('avatar')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <span class="text-muted text-sm">Recommended size: 200 × 200px</span>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Create Customer
                                </button>
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
