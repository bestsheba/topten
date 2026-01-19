@extends('admin.layouts.app')

@section('title')
    Add Product
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Add Product',
        'page' => 'Add Product',
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
                                Add Product
                            </h3>
                        </div>
                        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">
                                        Name
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        placeholder="Enter Name" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
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
                                </div>
                                <div class="form-group">
                                    <label for="price">
                                        Price
                                    </label>
                                    <input type="number" step="any" name="price"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        placeholder="Enter Price" value="{{ old('price') }}">
                                    @error('price')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="stock_quantity">
                                        Stock Quantity
                                    </label>
                                    <input type="number" name="stock_quantity"
                                        class="form-control @error('stock_quantity') is-invalid @enderror"
                                        id="stock_quantity" placeholder="Enter Stock Quantity"
                                        value="{{ old('stock_quantity') }}">
                                    @error('stock_quantity')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="namskue">
                                        SKU
                                    </label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        placeholder="Enter SKU" value="{{ old('sku', $sku) }}">
                                    @error('sku')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category">
                                        Select Category
                                    </label>
                                    <select name="category" id="category"
                                        class="form-control @error('category') is-invalid @enderror">
                                        @foreach ($categories as $category)
                                            <option @selected(old('category') == $category->id) value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
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
                                <div class="form-check">
                                    <input type="checkbox" name="active" class="form-check-input" id="active">
                                    <label class="form-check-label" for="active">
                                        Active
                                    </label>
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
