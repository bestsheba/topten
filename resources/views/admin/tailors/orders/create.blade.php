@extends('admin.layouts.app')

@section('title', 'Create Tailor Order')

@section('page')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            <div class="card shadow border-0">
                <div class="card-header text-white"
                     style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <h4 class="mb-0">
                        <i class="fas fa-cut me-2"></i> Create Tailor Order
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ url('/admin/tailor/orders') }}">
                        @csrf

                        {{-- Customer & Tailor --}}
                        <div class="mb-4">
                            <h6 class="text-primary fw-bold mb-3">Customer & Tailor</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id">
                                            Customer <span class="text-danger">*</span>
                                        </label>
                                        <select name="customer_id"
                                                id="customer_id"
                                                class="form-control @error('customer_id') is-invalid @enderror">
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tailor_id">
                                            Tailor <span class="text-danger">*</span>
                                        </label>
                                        <select name="tailor_id"
                                                id="tailor_id"
                                                class="form-control @error('tailor_id') is-invalid @enderror">
                                            <option value="">-- Select Tailor --</option>
                                            @foreach ($tailors as $tailor)
                                                <option value="{{ $tailor->id }}"
                                                    {{ old('tailor_id') == $tailor->id ? 'selected' : '' }}>
                                                    {{ $tailor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tailor_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Garment & Price --}}
                        <div class="mb-4">
                            <h6 class="text-primary fw-bold mb-3">Garment Details</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="garmentType">
                                            Garment Type <span class="text-danger">*</span>
                                        </label>
                                        <select name="garment_type_id"
                                                id="garmentType"
                                                class="form-control @error('garment_type_id') is-invalid @enderror">
                                            <option value="">-- Select Garment --</option>
                                            @foreach ($garmentTypes as $type)
                                                <option value="{{ $type->id }}"
                                                        data-price="{{ $type->price }}"
                                                    {{ old('garment_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('garment_type_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">
                                            Price <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="price"
                                               id="priceField"
                                               class="form-control @error('price') is-invalid @enderror"
                                               placeholder="Enter Price"
                                               value="{{ old('price') }}"
                                               step="0.01">
                                        @error('price')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Measurements --}}
                        <div class="mb-4">
                            <h6 class="text-primary fw-bold mb-3">Measurements</h6>

                            <div class="row" id="measurementFields">
                                <div class="col-12 text-muted">
                                    <small>Select a garment to load measurements</small>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end">
                            <a href="{{ url()->previous() }}"
                               class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-success px-5 ms-2">
                                <i class="fas fa-save me-1"></i> Save Order
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.getElementById('garmentType').addEventListener('change', function () {
        const garmentId = this.value;
        const price = this.options[this.selectedIndex]?.dataset.price || '';
        const priceField = document.getElementById('priceField');
        const container = document.getElementById('measurementFields');

        priceField.value = price;
        container.innerHTML = '';

        if (!garmentId) {
            container.innerHTML = `
                <div class="col-12 text-muted">
                    <small>Select a garment to load measurements</small>
                </div>`;
            return;
        }

        fetch(`/admin/tailor/measurements/${garmentId}`)
            .then(res => res.json())
            .then(fields => {
                fields.forEach(field => {
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    ${field.label} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="measurements[${field.label.toLowerCase().replace(/ /g, '_')}]"
                                       class="form-control"
                                       placeholder="Enter ${field.label}"
                                       required>
                            </div>
                        </div>
                    `;
                });
            });
    });
</script>
@endpush
