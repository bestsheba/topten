@extends('admin.layouts.app')

@section('title')
    Product Variations
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Product Variations',
        'page' => 'Variations',
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
                <div class="card-header">
                    <h5>Generated Variations for {{ $product->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>Base Price:</strong> 
                        <span class="fs-4 fw-bold text-primary">{{ number_format($product->price, 2) }} ৳</span>
                        <p class="mb-0 mt-2">
                            <small>This is the base price you set when creating the product. Variation prices cannot exceed this amount.</small>
                        </p>
                    </div>
                    <form action="{{ route('products.variations.store', $product) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        @foreach ($selectedAttributes as $attribute)
                                            <th>{{ $attribute->name }}</th>
                                        @endforeach
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variations as $variation)
                                        <tr>
                                            @foreach ($variation as $value)
                                                <td>
                                                    {{ $value->value }}
                                                    <input type="hidden"
                                                        name="variations[{{ $loop->parent->index }}][attribute_values][]"
                                                        value="{{ $value->id }}">
                                                </td>
                                            @endforeach
                                            <td>
                                                <input type="number" step="0.01" class="form-control price-input"
                                                    name="variations[{{ $loop->index }}][price]" 
                                                    max="{{ $product->price }}"
                                                    data-max-price="{{ $product->price }}"
                                                    placeholder="Max: {{ number_format($product->price, 2) }} ৳"
                                                    required>
                                                <small class="text-danger price-error" style="display: none;"></small>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="variations[{{ $loop->index }}][stock]" required>
                                            </td>
                                            <td>
                                                <input type="file" class="form-control"
                                                    name="variations[{{ $loop->index }}][image]"
                                                    onchange="previewImage(this)">
                                                <div class="image-preview mt-2" style="display: none;">
                                                    <img src="#" alt="Preview" width="50" class="img-thumbnail">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-variation"
                                                    data-row-index="{{ $loop->index }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Variations</button>
                        {{-- <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Cancel</a> --}}
                    </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            const maxPrice = {{ $product->price }};

            // Price validation for all price inputs
            document.querySelectorAll('.price-input').forEach(input => {
                input.addEventListener('input', function() {
                    validatePrice(this);
                });

                input.addEventListener('blur', function() {
                    validatePrice(this);
                });
            });

            // Validate price input
            function validatePrice(input) {
                const priceValue = parseFloat(input.value);
                const errorElement = input.parentElement.querySelector('.price-error');
                
                if (isNaN(priceValue) || priceValue <= 0) {
                    errorElement.textContent = 'Price must be greater than 0';
                    errorElement.style.display = 'block';
                    input.classList.add('is-invalid');
                    return false;
                } else if (priceValue > maxPrice) {
                    errorElement.textContent = `Price cannot exceed base price (${maxPrice.toFixed(2)} ৳)`;
                    errorElement.style.display = 'block';
                    input.classList.add('is-invalid');
                    input.value = maxPrice; // Auto-correct to max price
                    return false;
                } else {
                    errorElement.style.display = 'none';
                    input.classList.remove('is-invalid');
                    return true;
                }
            }

            // Form submission validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                document.querySelectorAll('.price-input').forEach(input => {
                    if (!validatePrice(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fix the price errors before submitting. Variation prices cannot exceed the base price of ' + maxPrice.toFixed(2) + ' ৳');
                    return false;
                }
            });

            // Add event listeners to all delete buttons
            document.querySelectorAll('.delete-variation').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the row and remove it
                    const row = this.closest('tr');
                    row.remove();

                    // Optional: Re-index the remaining rows if needed
                    reindexRows();
                });
            });

            function reindexRows() {
                // Get all rows
                const rows = document.querySelectorAll('tbody tr');

                // Loop through each row and update indices
                rows.forEach((row, index) => {
                    // Update all input names with new indices
                    row.querySelectorAll('input[name*="variations"]').forEach(input => {
                        let name = input.getAttribute('name');
                        name = name.replace(/variations\[\d+\]/, `variations[${index}]`);
                        input.setAttribute('name', name);
                    });

                    // Update delete button data attribute
                    const deleteBtn = row.querySelector('.delete-variation');
                    if (deleteBtn) {
                        deleteBtn.setAttribute('data-row-index', index);
                    }
                });
            }

            // Preview image function (you already had this referenced in your code)
            window.previewImage = function(input) {
                const preview = input.nextElementSibling.querySelector('img');
                const previewContainer = input.nextElementSibling;

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            };
        });
    </script>
@endsection
