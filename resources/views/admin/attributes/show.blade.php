@extends('admin.layouts.app')

@section('title')
    Product Details
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Product Details',
        'page' => 'Product Details',
    ])
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
@endsection
@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $product->name }}</h5>
                    <div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationsModal">
                            <i class="bi bi-plus"></i> Add Variations
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>Base Price:</strong> 
                        <span class="fs-4 fw-bold text-primary">{{ number_format($product->price, 2) }} ৳</span>
                        <p class="mb-0 mt-2">
                            <small>This is the base price you set when creating the product. Variation prices cannot exceed this amount.</small>
                        </p>
                    </div>
                    <h5 class="mt-4">Product Variations</h5>
                    @if ($product->variations->isEmpty())
                        <div class="alert alert-info">No variations added yet.</div>
                    @else
                        <form action="{{ route('products.variations.update', $product) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            @foreach ($attributes as $attribute)
                                                <th>{{ $attribute->name }}</th>
                                            @endforeach
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variations as $variation)
                                            <tr>
                                                <td>{{ $variation->sku }}</td>
                                                @foreach ($attributes as $attribute)
                                                    <td>
                                                        @foreach ($variation->attributeValues as $value)
                                                            @if ($value->attribute_id == $attribute->id)
                                                                {{ $value->value }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <input type="number" step="0.01" class="form-control price-input"
                                                        name="variations[{{ $variation->id }}][price]"
                                                        value="{{ $variation->price }}" 
                                                        max="{{ $product->price }}"
                                                        data-max-price="{{ $product->price }}"
                                                        placeholder="Max: {{ number_format($product->price, 2) }} ৳"
                                                        required>
                                                    <small class="text-danger price-error" style="display: none;"></small>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        name="variations[{{ $variation->id }}][stock]"
                                                        value="{{ $variation->stock }}" required>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($variation->image)
                                                            <img class="mr-1"
                                                                src="{{ asset('storage/' . $variation->image) }}"
                                                                alt="Variation Image" width="50"
                                                                class="img-thumbnail mb-2">
                                                        @endif
                                                        <input type="file" class="form-control"
                                                            name="variations[{{ $variation->id }}][image]">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('products.variations.destroy', [$product, $variation]) }}"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-x"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Variations</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Add Variations Modal -->
            <div class="modal fade" id="addVariationsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('products.variations.generate', $product) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Generate Variations</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Select Attributes</label>
                                    <div class="row">
                                        @foreach ($attributes as $attribute)
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="form-check">
                                                            <input class="form-check-input attribute-checkbox"
                                                                type="checkbox" id="attr-{{ $attribute->id }}"
                                                                name="attributes[]" value="{{ $attribute->id }}">
                                                            <label class="form-check-label fw-bold"
                                                                for="attr-{{ $attribute->id }}">
                                                                {{ $attribute->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach ($attribute->values as $value)
                                                            <div class="form-check">
                                                                <input class="form-check-input value-checkbox"
                                                                    type="checkbox" id="value-{{ $value->id }}"
                                                                    name="values[]" value="{{ $value->id }}"
                                                                    data-attribute="{{ $attribute->id }}">
                                                                <label class="form-check-label"
                                                                    for="value-{{ $value->id }}">
                                                                    {{ $value->value }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Generate Variations</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Product Information</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Product Image -->
                                <div class="col-md-4">
                                    <img src="{{ $product->picture_url }}" alt="{{ $product->name }}"
                                        class="img-fluid rounded">
                                </div>
                                <!-- Product Details -->
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="200">Name</th>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>SKU</th>
                                            <td>{{ $product->sku }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $product->category->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Category</th>
                                            <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Brand</th>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Base Price</th>
                                            <td>{{ showAmount($product->price) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Stock Quantity</th>
                                            <td>{{ $product->stock_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($product->discount)
                                            <tr>
                                                <th>Discount</th>
                                                <td>
                                                    {{ $product->discount }}
                                                    {{ $product->discount_type === 'percentage' ? '%' : showAmount($product->discount) }}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>

                                    <!-- Description -->
                                    <div class="mt-4">
                                        <h5>Description</h5>
                                        <div class="p-3 bg-light rounded">
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <h5>Product Video:</h5>
                                        @if (!empty($product->video))
                                            <div class="mt-4">
                                                <video width="400" controls>
                                                    <source src="{{ asset('storage/' . $product->video) }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Product Gallery -->
                            @if ($product->galleries->count() > 0)
                                <div class="mt-4">
                                    <h5>Product Gallery</h5>
                                    <div class="row">
                                        @foreach ($product->galleries as $gallery)
                                            <div class="col-md-2 col-sm-4 col-6 mb-3">
                                                <img src="{{ $gallery->picture_url }}" alt="Gallery Image"
                                                    class="img-fluid rounded" style="height: 120px">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            const maxPrice = {{ $product->price }};

            // Price validation for all price inputs
            $('.price-input').on('input blur', function() {
                validatePrice(this);
            });

            // Validate price input
            function validatePrice(input) {
                const $input = $(input);
                const priceValue = parseFloat($input.val());
                const $errorElement = $input.parent().find('.price-error');
                
                if (isNaN(priceValue) || priceValue <= 0) {
                    $errorElement.text('Price must be greater than 0');
                    $errorElement.show();
                    $input.addClass('is-invalid');
                    return false;
                } else if (priceValue > maxPrice) {
                    $errorElement.text('Price cannot exceed base price (' + maxPrice.toFixed(2) + ' ৳)');
                    $errorElement.show();
                    $input.addClass('is-invalid');
                    $input.val(maxPrice); // Auto-correct to max price
                    return false;
                } else {
                    $errorElement.hide();
                    $input.removeClass('is-invalid');
                    return true;
                }
            }

            // Form submission validation
            $('form').on('submit', function(e) {
                let isValid = true;
                $('.price-input').each(function() {
                    if (!validatePrice(this)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fix the price errors before submitting. Variation prices cannot exceed the base price of ' + maxPrice.toFixed(2) + ' ৳');
                    return false;
                }
            });

            // Enable/disable value checkboxes based on attribute selection
            $('.attribute-checkbox').change(function() {
                const attributeId = $(this).val();
                const isChecked = $(this).is(':checked');

                $(`.value-checkbox[data-attribute="${attributeId}"]`).prop('disabled', !isChecked);

                if (!isChecked) {
                    $(`.value-checkbox[data-attribute="${attributeId}"]`).prop('checked', false);
                }
            });
        });
    </script>
@endsection
