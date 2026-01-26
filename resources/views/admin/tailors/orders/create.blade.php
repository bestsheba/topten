@extends('admin.layouts.app')

@section('title', 'Create Tailor Order')

@section('page')
    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12">

                <div class="card shadow border-0">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                        <h4 class="mb-0">
                            <i class="fas fa-cut me-2"></i> Create Service Order
                        </h4>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ url('/admin/tailor/orders') }}">
                            @csrf
                            <div>
                                <div class="row">
                                <div class="col-md-6">
    <div class="form-group">
        <label>
            Customer <span class="text-danger">*</span>
        </label>

        <div class="input-group">
            <select name="customer_id" id="customer_id"
                class="form-control @error('customer_id') is-invalid @enderror">
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>

            {{-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addCustomerModal">
                <i class="fas fa-plus"></i>
            </button> --}}
        </div>

        @error('customer_id')
            <span class="error invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tailor_id">
                                                Tailor <span class="text-danger">*</span>
                                            </label>
                                            <select name="tailor_id" id="tailor_id"
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">
                                                Service Price <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="price" id="priceField"
                                                class="form-control @error('price') is-invalid @enderror"
                                                placeholder="Enter Price" value="{{ old('price') }}" step="0.01">
                                            @error('price')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cash">
                                                Collected Cash
                                            </label>
                                            <input type="number" name="cash" id="cashField"
                                                class="form-control @error('cash') is-invalid @enderror"
                                                placeholder="Enter Price" value="{{ old('cash') }}" step="0.01">
                                            @error('cash')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                Garment Type <span class="text-danger">*</span>
                                            </label>

                                            {{-- Hidden input for form submission --}}
                                            <input type="hidden" name="garment_type_id" id="garment_type_id"
                                                value="{{ old('garment_type_id') }}">

                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($garmentTypes as $type)
                                                    <button type="button"
                                                        class="btn garment-btn mr-2
                        {{ old('garment_type_id') == $type->id ? 'btn-primary' : 'btn-outline-primary' }}"
                                                        data-id="{{ $type->id }}" data-price="{{ $type->price }}">
                                                        {{ $type->name }}
                                                    </button>
                                                @endforeach
                                            </div>

                                            @error('garment_type_id')
                                                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="row" id="measurementFields">
                                    <div class="col-12 text-muted">
                                        <small>Select a garment to load measurements</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success px-5 ms-2">
                                    <i class="fas fa-save me-1"></i> Save Order
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="addCustomerForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone *</label>
                            <input type="text" name="phone_number" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Address *</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Save Customer
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch("{{ route('admin.customers.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.customer) {
                // Append to select
                const option = document.createElement('option');
                option.value = data.customer.id;
                option.text = data.customer.name;
                option.selected = true;

                document.getElementById('customer_id').appendChild(option);

                // Close modal & reset
                bootstrap.Modal.getInstance(
                    document.getElementById('addCustomerModal')
                ).hide();

                form.reset();
            }
        })
        .catch(() => {
            alert('Failed to add customer');
        });
    });
</script>

    <script>
        const priceField = document.getElementById('priceField');
        const container = document.getElementById('measurementFields');
        const hiddenGarmentInput = document.getElementById('garment_type_id');

        document.querySelectorAll('.garment-btn').forEach(button => {
            button.addEventListener('click', function() {

                // Reset button states
                document.querySelectorAll('.garment-btn').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });

                // Activate selected button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

                const garmentId = this.dataset.id;
                const price = this.dataset.price ?? '';

                // Set hidden input & price
                hiddenGarmentInput.value = garmentId;
                priceField.value = price;

                // Reset measurements
                container.innerHTML = '';

                if (!garmentId) {
                    container.innerHTML = `
                    <div class="col-12 text-muted">
                        <small>Select a garment to load measurements</small>
                    </div>`;
                    return;
                }

                // Fetch measurements
                fetch(`/admin/tailor/measurements/${garmentId}`)
                    .then(res => res.json())
                    .then(fields => {
                        if (!fields.length) {
                            container.innerHTML = `
                            <div class="col-12 text-muted">
                                <small>No measurements required for this garment</small>
                            </div>`;
                            return;
                        }

                        fields.forEach(field => {
                            container.innerHTML += `
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        ${field.label}
                                    </label>
                                    <input
                                        type="text"
                                        name="measurements[${field.label.toLowerCase().replace(/ /g, '_')}]"
                                        class="form-control"
                                        placeholder="Enter ${field.label}"
                                    >
                                </div>
                            </div>`;
                        });
                    })
                    .catch(() => {
                        container.innerHTML = `
                        <div class="col-12 text-danger">
                            <small>Failed to load measurements</small>
                        </div>`;
                    });
            });
        });
    </script>
@endpush
