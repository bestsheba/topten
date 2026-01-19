@extends('admin.layouts.app')

@section('title')
    SEO
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'SEO',
        'page' => 'SEO',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="post">
                                <form action="{{ route('admin.update-seo') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="tab" value="basic" id="">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="seo_title" class="col-form-label">
                                                SEO Title
                                            </label>
                                            <input type="text" id="seo_title"
                                                value="{{ old('seo_title', $settings?->seo_title) }}" name="seo_title"
                                                class="form-control @error('seo_title') is-invalid @enderror"
                                                placeholder="SEO Title">
                                            @error('seo_title')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            <small class="text-muted">
                                                This title must be 60 characters or less.
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label for="seo_description" class="col-form-label">
                                                SEO Description
                                            </label>
                                            <textarea name="seo_description" id="seo_description"
                                                class="form-control @error('seo_description') is-invalid @enderror" placeholder="SEO Description">{{ old('seo_description', $settings?->seo_description) }}</textarea>
                                            @error('seo_description')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            <small class="text-muted">
                                                This description must be 155 characters or less.
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label for="seo_keywords" class="col-form-label">
                                                Keywords
                                            </label>
                                            <input class="form-control @error('seo_keywords') is-invalid @enderror"
                                                type="text" value="{{ old('seo_keywords', $settings?->seo_keywords) }}"
                                                name="seo_keywords" id="seo_keywords" placeholder="Keywords">
                                            @error('seo_keywords')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            <small class="text-muted">
                                                Keywords must be separated by commas.
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label for="banner_image" class="col-form-label">
                                                Banner Image
                                            </label>
                                            <input class="form-control @error('banner_image') is-invalid @enderror"
                                                type="file" name="banner_image" id="banner_image" accept="image/*"
                                                style="height: unset">
                                            @error('banner_image')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                            <small class="text-muted">
                                                This image size must be 1200x630px.
                                            </small>
                                        </div>
                                        @if ($settings?->seo_banner_image)
                                            <br>
                                            <div class="form-group">
                                                <img src="{{ asset($settings?->seo_banner_image) }}" alt="Banner Image"
                                                    class="img-fluid">
                                            </div>
                                        @endif
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
            </div>
        </div>
    </section>
@endsection
