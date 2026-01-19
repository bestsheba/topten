@extends('admin.layouts.app')

@section('title')
    Add Custom Page
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Custom Page',
        'page' => 'Add Custom Page',
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
                                Add Custom Page
                            </h3>
                        </div>
                        <form action="{{ route('admin.custom-page.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="group">Group</label>
                                    <select name="custom_page_group_id" id="group" class="form-control">
                                        <option value="">Select Group</option>
                                        @foreach(\App\Models\CustomPageGroup::ordered()->get() as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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

                                <div class="form-group">
                                    <label for="link">External Link</label>
                                    <input type="url" name="link"
                                        class="form-control @error('link') is-invalid @enderror" id="link"
                                        placeholder="https://facebook.com/yourpage (optional)" value="{{ old('link') }}">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Enter external link URL (e.g., Facebook, Instagram, website). Leave empty if not needed.
                                    </small>
                                    @error('link')
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

@section('script')
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
    </script>
@endsection
