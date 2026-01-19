@extends('admin.layouts.app')

@section('title')
    Add Blog
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Blog',
        'page' => 'Add Blog',
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
                                Add Blog
                            </h3>
                        </div>
                        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        placeholder="Enter title" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail">
                                        Select Thumbnail
                                    </label>
                                    <input type="file" name="thumbnail"
                                        class="form-control @error('thumbnail') is-invalid @enderror"
                                        style="line-height: 1.2" accept="image/*">
                                    @error('thumbnail')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    {{-- <span class="text-muted text-sm">360 × 230px is recommended size</span> --}}
                                </div>

                                <div class="form-group">
                                    <label for="editor">
                                        Description
                                    </label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="editor"
                                        placeholder="Enter Description">{{ old('description') }}</textarea>
                                    @error('description')
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2-bootstrap.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/ckeditor.min.js') }}"></script>
    <script>
        let ckeditor;
        ClassicEditor.create(document.querySelector("#editor"), {
                // plugins: [ Font ],
                // toolbar: [ 'fontColor' ], // uncommenting this breaks things and throws toolbarview-item-unavailable error
            })
            .then((editor) => {
                // console.log(Array.from(
                //   editor.ui.componentFactory.names()
                // ));
                ckeditor = editor;
            })
            .catch((error) => {
                console.error(error);
            });

        $(document).ready(function() {
            $('#category').select2({
                theme: "bootstrap"
            });
        });
    </script>
@endsection
