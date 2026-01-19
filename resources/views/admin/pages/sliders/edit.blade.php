@extends('admin.layouts.app')

@section('title')
    Edit sliders
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit sliders',
        'page' => 'Edit sliders',
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
                                Edit sliders
                            </h3>
                        </div>
                        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $slider->title) }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control">{{ old('description', $slider->description) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control">
                                <img src="{{ asset($slider->image) }}" width="100" alt="Slider Image">
                            </div>
                            <div class="form-group">
                                <label for="link" class="col-form-label">
                                    Link
                                </label>
                                <input class="form-control @error('link') is-invalid @enderror" type="url"
                                    name="link" value="{{ old('link', $slider->link) }}"
                                    placeholder="Link" id="link">
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
