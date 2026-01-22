@extends('admin.layouts.app')

@section('title')
    Edit Product
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit Product',
        'page' => 'Edit Product',
    ])
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2-bootstrap.min.css') }}">
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Edit Product
                            </h3>
                        </div>
                        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <input type="hidden" name="scroll_height" id="scroll_height" value="" readonly>

                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name"
                                        value="{{ old('name', $product->name) }}">
                                    @error('name')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Buying Price --}}
                                <div class="form-group">
                                    <label for="buying_price">Buying Price</label>
                                    <input type="number" step="any" name="buying_price" id="buying_price"
                                        class="form-control @error('buying_price') is-invalid @enderror"
                                        placeholder="Enter Buying Price"
                                        value="{{ old('buying_price', $product->buying_price) }}">
                                    @error('buying_price')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Selling Price --}}
                                <div class="form-group">
                                    <label for="selling_price">Selling Price</label>
                                    <input type="number" step="any" name="selling_price" id="selling_price"
                                        class="form-control @error('selling_price') is-invalid @enderror"
                                        placeholder="Enter Selling Price"
                                        value="{{ old('selling_price', $product->selling_price) }}">
                                    @error('selling_price')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Stock Quantity --}}
                                <div class="form-group">
                                    <label for="stock_quantity">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity"
                                        class="form-control @error('stock_quantity') is-invalid @enderror"
                                        placeholder="Enter Stock Quantity"
                                        value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                    @error('stock_quantity')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- SKU --}}
                                <div class="form-group">
                                    <label for="sku">SKU</label>
                                    <input type="text" name="sku" id="sku"
                                        class="form-control @error('sku') is-invalid @enderror" placeholder="Enter SKU"
                                        value="{{ old('sku', $product->sku) }}">
                                    @error('sku')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Brand --}}
                                <div class="form-group">
                                    <label for="brand">Select Brand (optional)</label>
                                    <select name="brand" id="brand"
                                        class="form-control @error('brand') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" @selected(old('brand', $product->brand_id) == $brand->id)>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Category --}}
                                <div class="form-group">
                                    <label for="category">Select Category</label>
                                    <select name="category" id="category"
                                        class="form-control @error('category') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category', $product->category_id) == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Sub Category --}}
                                {{-- <div class="form-group">
                                    <label for="sub_category">Select Sub Category</label>
                                    <select name="sub_category" id="sub_category"
                                        class="form-control @error('sub_category') is-invalid @enderror">
                                    </select>
                                    @error('sub_category')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div> --}}

                                {{-- Description --}}
                                <div class="form-group">
                                    <label for="editor">Description</label>
                                    <textarea name="description" id="editor" class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Enter Description">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Image --}}
                                <div class="form-group">
                                    <label>Product Image (optional)</label>
                                    <input type="file" name="picture"
                                        class="form-control @error('picture') is-invalid @enderror" accept="image/*">
                                    @error('picture')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class="text-muted text-sm">400 × 300px recommended</span>
                                </div>

                                {{-- Active --}}
                                {{-- <div class="form-check">
                                    <input type="checkbox" name="active" id="active" class="form-check-input"
                                        {{ old('active', $product->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Active</label>
                                </div> --}}
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update Product
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
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/ckeditor.min.js') }}"></script>
    <script>
        let ckeditor;
        let sizeGuideEditor;
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

        // Initialize Size Guide Editor
        ClassicEditor.create(document.querySelector("#size_guide_editor"), {
                // plugins: [ Font ],
                // toolbar: [ 'fontColor' ], // uncommenting this breaks things and throws toolbarview-item-unavailable error
            })
            .then((editor) => {
                sizeGuideEditor = editor;
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

        // Size checkbox handling
        $('.size-checkbox').change(function() {
            const details = $(this).closest('.size-item').find('.size-details');
            if ($(this).is(':checked')) {
                details.removeClass('d-none');
            } else {
                details.addClass('d-none');
            }
        });

        // Color checkbox handling
        $('.color-checkbox').change(function() {
            const details = $(this).closest('.color-item').find('.color-details');
            if ($(this).is(':checked')) {
                details.removeClass('d-none');
            } else {
                details.addClass('d-none');
            }
        });
    </script>
@endsection
