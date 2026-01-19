@extends('admin.layouts.app')
@section('title')
    Tailor Create
@endsection
@section('page')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tailor Order Form</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ url('/tailor/orders') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Garment Type</label>
                        <select name="garment_type_id" id="garmentType" class="form-select" required>
                            <option value="">-- Select Garment --</option>
                            @foreach ($garmentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row" id="measurementFields"></div>

                    <button class="btn btn-success mt-3">
                        Save Order
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById('garmentType').addEventListener('change', function() {
            const garmentId = this.value;
            const container = document.getElementById('measurementFields');
            container.innerHTML = '';

            if (!garmentId) return;

            fetch(`/tailor/measurements/${garmentId}`)
                .then(res => res.json())
                .then(fields => {
                    fields.forEach(field => {
                        container.innerHTML += `
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            ${field.label}
                        </label>
                        <input type="text"
                               name="measurements[${field.key}]"
                               class="form-control"
                               required>
                    </div>
                `;
                    });
                });
        });
    </script>
@endpush
