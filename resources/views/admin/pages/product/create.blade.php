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
                        <form action="{{ route('admin.product.store') }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="scroll_height" id="scroll_height" value="" readonly>
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
                                    <label for="discount">
                                        Discount
                                    </label>
                                    <input type="number" step="any" name="discount"
                                        class="form-control @error('discount') is-invalid @enderror" id="discount"
                                        placeholder="Enter Discount" value="{{ old('discount') }}">
                                    @error('discount')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="discount_type">
                                        Discount Type
                                    </label>
                                    <select name="discount_type" id="discount_type"
                                        class="form-control @error('discount_type') is-invalid @enderror">
                                        <option value="amount">
                                            Amount
                                        </option>
                                        <option value="percentage">
                                            Percentage
                                        </option>
                                    </select>
                                    @error('discount_type')
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
                                        Select Brand (optional)
                                    </label>
                                    <select name="brand" id="brand"
                                        class="form-control @error('brand') is-invalid @enderror">
                                        <option value="" selected>Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option @selected(request('brand_id') == $brand->id) value="{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand')
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
                                        <option value="">
                                            Select Category
                                        </option>
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
                                    <label for="category">
                                        Select Sub Category
                                    </label>
                                    <select name="sub_category" id="sub_category"
                                        class="form-control @error('sub_category') is-invalid @enderror">
                                    </select>
                                    @error('sub_category')
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
                                    <label for="size_guide_editor">
                                        Size Guide (optional)
                                    </label>
                                    <textarea name="size_guide" class="form-control @error('size_guide') is-invalid @enderror" id="size_guide_editor"
                                        placeholder="Write product-specific size guide or paste HTML table">{{ old('size_guide') }}</textarea>
                                    @error('size_guide')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <small class="text-muted">If provided, a Size Guide link will appear on the product page.</small>
                                </div>
                                <div class="form-group">
                                    <label for="meta_title">
                                        Meta Title (optional)
                                    </label>
                                    <input type="text" name="meta_title"
                                        class="form-control @error('meta_title') is-invalid @enderror" id="meta_title"
                                        placeholder="Enter Meta Title" value="{{ old('meta_title') }}">
                                    @error('meta_title')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">
                                        Meta Description (optional)
                                    </label>
                                    <textarea name="meta_description" 
                                        class="form-control @error('meta_description') is-invalid @enderror" 
                                        id="meta_description" 
                                        placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="meta_keywords">
                                        Meta Keywords (optional, comma-separated)
                                    </label>
                                    <textarea name="meta_keywords" 
                                        class="form-control @error('meta_keywords') is-invalid @enderror" 
                                        id="meta_keywords" 
                                        placeholder="Enter Meta Keywords (e.g., product, category, brand)">{{ old('meta_keywords') }}</textarea>
                                    @error('meta_keywords')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <small class="text-muted">Separate keywords with commas</small>
                                </div>
                                <div class="form-group">
                                    <label for="meta_image">
                                        Meta Image (optional)
                                    </label>
                                    <input type="file" name="meta_image"
                                        class="form-control @error('meta_image') is-invalid @enderror"
                                        style="line-height: 1.2" accept="image/*">
                                    @error('meta_image')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <span class="text-muted text-sm">Recommended size: 1200 × 630px</span>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="video">Product Video (optional)</label>
                                    <input type="file" name="video"
                                        class="form-control-file @error('video') is-invalid @enderror" accept="video/*">
                                    @error('video')
                                        <span class="error invalid-feedback d-block">{{ $message }}</span>
                                    @enderror

                                    @if (!empty($product->video))
                                        <video controls class="mt-2" width="320">
                                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="picture">
                                        Select Picture
                                    </label>
                                    <input type="file" name="picture"
                                        class="form-control @error('picture') is-invalid @enderror"
                                        style="line-height: 1.2" accept="image/*">
                                    @error('picture')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <span class="text-muted text-sm">400 × 300px is recommended size</span>
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
        // Scroll to saved position if 'scroll' parameter exists in the URL
        $(document).ready(function() {
            var params = new URLSearchParams(window.location.search);
            var scrollPosition = params.get('scroll');

            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#category').select2({
                theme: "bootstrap"
            });
            $('#sub_category').select2({
                theme: "bootstrap"
            });

            // Event listener for category change
            $('#category').on('change', function() {
                var categoryId = $(this).val();

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('admin.getSubcategories') }}", // Define this route in web.php
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            $('#sub_category').empty();
                            $('#sub_category').append(
                                '<option value="">Select Sub Category</option>');

                            $.each(response, function(key, subCategory) {
                                $('#sub_category').append('<option value="' +
                                    subCategory.id + '">' + subCategory.name +
                                    '</option>');
                            });

                            $('#sub_category').trigger('change'); // Refresh Select2
                        }
                    });
                } else {
                    $('#sub_category').empty();
                    $('#sub_category').append('<option value="">Select Sub Category</option>');
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
        });
    </script>
@endsection
