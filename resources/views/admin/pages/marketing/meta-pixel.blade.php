@extends('admin.layouts.app')

@section('title')
    Meta Pixel
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Meta Pixel',
        'page' => 'Meta Pixel',
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
                                <form action="{{ route('admin.update-meta-pixel') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="tab" value="basic" id="">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="meta_pixel" class="col-form-label">
                                                Meta Pixel Id
                                            </label>
                                            <input type="text" id="meta_pixel"
                                                value="{{ old('meta_pixel', $settings?->meta_pixel) }}" name="meta_pixel"
                                                class="form-control @error('meta_pixel') is-invalid @enderror"
                                                placeholder="303887744xxxxxx">
                                            @error('meta_pixel')
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
            </div>
        </div>
    </section>
@endsection
