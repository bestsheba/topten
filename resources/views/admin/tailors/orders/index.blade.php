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
                                <th>Customer</th>
                                <th>Tailor</th>
                                <th>Garment</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->tailor->name }}</td>
                                    <td>{{ $order->garmentType->name }}</td>
                                    <td>{{ number_format($order->price, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tailor.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
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
@endsection
@push('script')
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="order_ids[]"]').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    </script>
@endpush
