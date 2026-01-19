@extends('admin.layouts.app')

@section('title')
    Add Slider
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Slider',
        'page' => 'Add Slider',
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
                                Add Slider
                            </h3>
                        </div>
                        <form action="{{ route('admin.offer-slider.store') }}" method="POST" id="form" class="p-4"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control">
                                @error('image')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="link" class="col-form-label">
                                    Link
                                </label>
                                <input class="form-control @error('link') is-invalid @enderror" type="url"
                                    name="link" value="{{ old('link') }}" placeholder="Link" id="link">
                                @error('link')
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
