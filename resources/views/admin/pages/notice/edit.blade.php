@extends('admin.layouts.app')

@section('title')
    Edit notice
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit notice',
        'page' => 'Edit notice',
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
                                Edit notice
                            </h3>
                        </div>
                        <form action="{{ route('admin.notice.update', $notice->id) }}" id="form" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="editor">
                                        Description
                                    </label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="editor"
                                        placeholder="Enter Description">{{ old('description', $notice->description) }}</textarea>
                                    @error('description')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
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
        ClassicEditor.create(document.querySelector("#editor"), {})
            .then((editor) => {
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

        $(document).ready(function() {
            $('#brand').select2({
                theme: "bootstrap"
            });
        });
        $(document).ready(function() {
            $('#sub_category').select2({
                theme: "bootstrap"
            });
        });

        // Event listener for select change
        $('#category').on('change', function() {
            var selectedValue = $(this).val();

            var scroll = window.scrollY;
            $('#scroll_height').val(scroll);

            $('#form').submit();
        });

        // Scroll to saved position if 'scroll' parameter exists in the URL
        $(document).ready(function() {
            var params = new URLSearchParams(window.location.search);
            var scrollPosition = params.get('scroll');

            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
            }
        });
    </script>
@endsection
