@extends('admin.layouts.app')

@section('title')
    Edit Slider
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit Slider',
        'page' => 'Edit Slider',
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
                                Edit Slider
                            </h3>
                        </div>
                        <form action="{{ route('admin.offer-slider.update', $offer_slider->id) }}" method="POST"
                            enctype="multipart/form-data" class="p-4">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $offer_slider->title) }}">
                                @error('title')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control">
                                <img src="{{ asset($offer_slider->image) }}" width="100" alt="Slider Image">
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
                                    name="link" value="{{ old('link', $offer_slider->link) }}" placeholder="Link"
                                    id="link">
                                @error('link')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
