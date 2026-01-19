@extends('admin.layouts.app')

@section('title')
    Add Service
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Service',
        'page' => 'Add Service',
    ])
@endsection
@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Add New Service
                            </h3>
                        </div>
                        <form action="{{ route('admin.services.store') }}" method="POST">
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
                                                placeholder="Enter Service Name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">
                                            Price <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="price"
                                            class="form-control @error('price') is-invalid @enderror" id="price"
                                            placeholder="Enter Price" value="{{ old('price') }}">
                                        @error('price')
                                            <span class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer" style="background: transparent; padding-left: 0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Create Service
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
