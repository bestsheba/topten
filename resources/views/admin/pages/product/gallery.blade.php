@extends('admin.layouts.app')

@section('title')
    Gallery
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Gallery',
        'page' => 'Gallery',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Gallery
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Picture</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($galleries as $gallery)
                                        <tr>
                                            <td>
                                                {{ $gallery->id }}
                                            </td>
                                            <td>
                                                <img class="rounded img-fluid" width="50" height="50"
                                                    src="{{ $gallery->picture_url }}" alt="gallery">
                                            </td>
                                            <td>
                                                <div class="d-flex items-align-center">
                                                    <form action="{{ route('admin.gallery.destroy', $gallery->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                                            class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                No Data ....
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $galleries->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Add New
                            </h3>
                        </div>
                        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ request('product') }}" name="product" readonly>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="picture">
                                        Select Picture
                                    </label>
                                    <input type="file" name="picture"
                                        class="form-control @error('picture') is-invalid @enderror" style="line-height: 1.2"
                                        accept="image/*">
                                    @error('picture')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <span class="text-muted text-sm">280 × 280px is recommended size</span>
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
