@extends('admin.layouts.app')

@section('title', 'Tailor Orders')

@section('page')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Tailor Orders</h4>

            <a href="{{ route('admin.tailor.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Order
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <form method="POST" action="{{ route('admin.tailor.orders.invoice') }}">
                    @csrf
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Tailor</th>
                                <th>Garment</th>
                                <th>Price</th>
                                <th>Collected</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th width="170">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $paid = $order->paid_amount ?? 0;
                                    $due = $order->price - $paid;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}"></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->tailor->name }}</td>
                                    <td>{{ $order->garmentType->name }}</td>

                                    <td>{{ number_format($order->price, 2) }}</td>
                                    <td class="text-success">{{ number_format($paid, 2) }}</td>
                                    <td class="text-danger">{{ number_format($due, 2) }}</td>

                                    <td>
                                        @if ($due == 0)
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($paid > 0)
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Due</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tailor.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-info">
                                            View
                                        </a>

                                        @if ($due > 0)
                                            <button type="button" class="btn btn-sm btn-warning pay-due-btn"
                                                data-id="{{ $order->id }}" data-due="{{ $due }}"
                                                data-bs-toggle="modal" data-bs-target="#payDueModal">
                                                Pay Due
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button class="btn btn-primary mt-3">
                        Generate Invoice
                    </button>
                </form>
                {{ $orders->links() }}

            </div>
        </div>
    </div>
    <div class="modal fade" id="payDueModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="payDueForm">
                    @csrf

                    <input type="hidden" name="order_id" id="due_order_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Pay Due Amount</h5>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Due Amount</label>
                            <input type="text" id="due_amount_display" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Pay Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="pay_amount" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Confirm Payment
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
        document.querySelectorAll('.pay-due-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                document.getElementById('due_order_id').value = this.dataset.id;
                document.getElementById('due_amount_display').value =
                    parseFloat(this.dataset.due).toFixed(2);

                const modal = new bootstrap.Modal(
                    document.getElementById('payDueModal')
                );
                modal.show();
            });
        });


        document.getElementById('payDueForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("{{ route('admin.tailor.orders.pay-due') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(() => location.reload())
                .catch(() => alert('Payment failed'));
        });
    </script>
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="order_ids[]"]').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    </script>
@endpush
