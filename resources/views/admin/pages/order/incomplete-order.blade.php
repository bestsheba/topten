@extends('admin.layouts.app')

@section('title')
    Incomplete Orders
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Incomplete Orders',
        'page' => 'Incomplete Orders',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Incomplete Orders
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>
                                        <th>Phone Number</th>
                                        <th>Customer Address</th>
                                        <th>Products</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ date('d F, Y h:i:s A', strtotime($order->created_at)) }}
                                            </td>
                                            <td>
                                                {{ $order->name }}
                                            </td>
                                            <td>
                                                {{ $order->phone_number ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $order->address ?? '-' }}
                                            </td>
                                            <td>
                                                @if (count($order->products) > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="modal" data-target="#productModal{{ $order->id }}">
                                                        View Products
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.order.incomplete.place', $order->id) }}"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="btn btn-outline-info mr-1" title="Place Order">
                                                        Place Order
                                                    </a>
                                                    <form action="{{ route('admin.order.incomplete.delete', $order->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                                            class="btn btn-outline-danger mr-1" title="Delete">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                No Data ....
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($orders as $order)
        @if (count($order->products) > 0)
            <!-- Product Modal -->
            <div class="modal fade" id="productModal{{ $order->id }}" tabindex="-1" role="dialog"
                aria-labelledby="productModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="productModalLabel{{ $order->id }}">
                                Products in Order #{{ $order->id }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach ($order->products as $key => $product)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            <div class="row no-gutters">
                                                <div class="col-4">
                                                    <img src="{{ asset($product['product']['picture']) }}"
                                                        class="img-fluid rounded-left"
                                                        alt="{{ $product['product']['name'] }}" style="max-height: 72px">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body py-2">
                                                        <h6 class="card-title mb-1">
                                                            {{ $product['product']['name'] }}
                                                        </h6>
                                                        <p class="mb-0">
                                                            <strong>Price:</strong>
                                                            {{ showAmount($product['product']['price']) }}
                                                        </p>
                                                        @if (isset($product['quantity']))
                                                            <p class="mb-0">
                                                                <strong>Quantity:</strong>
                                                                {{ $product['quantity'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productViewModal = document.getElementById('productViewModal');
            productViewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const name = button.getAttribute('data-name');
                const image = button.getAttribute('data-image');
                const description = button.getAttribute('data-description');

                productViewModal.querySelector('#modalProductImage').src = image;
                productViewModal.querySelector('#modalProductName').textContent = name;
                productViewModal.querySelector('#modalProductDescription').textContent = description;
            });
        });
    </script>
@endsection
