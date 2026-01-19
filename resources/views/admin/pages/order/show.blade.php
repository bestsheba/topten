@extends('admin.layouts.app')

@section('title')
    Orders
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Orders',
        'page' => 'Orders',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary border-bottom-3">
                            <h3 class="card-title text-white font-weight-bold">
                                <i class="fas fa-receipt"></i> Order Details
                            </h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="p-md-4">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h4 class="font-weight-bold">
                                            <i class="fas fa-globe text-primary"></i> {{ $settings->website_name }}
                                            <small class="float-right text-muted">Date:
                                                {{ date('d/m/Y', strtotime(now())) }}</small>
                                        </h4>
                                    </div>
                                </div>
                                <div class="row invoice-info mb-4">
                                    <div class="col-sm-4 invoice-col mb-3">
                                        <div class="card border-left-primary h-100 border-0"
                                            style="border-top: 4px solid #007bff !important;">
                                            <div class="card-body">
                                                <h6 class="text-primary font-weight-bold mb-3">
                                                    <i class="fas fa-store"></i> From
                                                </h6>
                                                <address class="mb-0">
                                                    <strong>{{ $settings->website_name }}</strong><br>
                                                    <small class="text-muted">{{ $settings->address ?? '-' }}</small><br>
                                                    <small class="text-muted"><i class="fas fa-phone"></i>
                                                        {{ $settings->phone_number ?? '-' }}</small><br>
                                                    <small class="text-muted"><i class="fas fa-envelope"></i>
                                                        {{ $settings->email ?? '-' }}</small>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 invoice-col mb-3">
                                        <div class="card border-left-success h-100 border-0"
                                            style="border-top: 4px solid #28a745 !important;">
                                            <div class="card-body">
                                                <h6 class="text-success font-weight-bold mb-3">
                                                    <i class="fas fa-user"></i> To
                                                </h6>
                                                <address class="mb-0">
                                                    <strong>{{ $order->customer_name }} </strong><br>
                                                    <small
                                                        class="text-muted">{{ $order->customer_address ?? '-' }}</small><br>
                                                    <small class="text-muted"><i class="fas fa-phone"></i>
                                                        {{ $order->customer_phone_number ?? '-' }}</small>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 invoice-col mb-3">
                                        <div class="card border-left-warning h-100 border-0"
                                            style="border-top: 4px solid #ffc107 !important;">
                                            <div class="card-body">
                                                <h6 class="text-warning font-weight-bold mb-3">
                                                    <i class="fas fa-info-circle"></i> Order Info
                                                </h6>
                                                <p class="mb-2">
                                                    <b>Order ID:</b>
                                                    <span id="order-id"
                                                        class="text-monospace">{{ $order->hashed_id ?? '-' }}</span>
                                                    <button class="btn btn-sm btn-outline-primary" id="copy-order-id-btn"
                                                        title="Copy Order ID">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </p>
                                                <p class="mb-2">
                                                    <b>Status:</b> {!! $order->status_b_value !!}
                                                </p>
                                                <p class="mb-2">
                                                    <b>Placed Date:</b> {{ date('d/m/Y', strtotime($order->created_at)) }}
                                                </p>
                                                @if ($order->payment_transaction_id)
                                                    <p class="mb-0">
                                                        <b>Transaction ID:</b> <small
                                                            class="text-monospace">{{ $order->payment_transaction_id }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="font-weight-bold mb-3">
                                            <i class="fas fa-box"></i> Order Items
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered border-secondary">
                                                <thead class="bg-dark text-white border-bottom-2">
                                                    <tr>
                                                        <th width="5%" class="text-center"><i
                                                                class="fas fa-hashtag"></i></th>
                                                        <th width="10%" class="text-center">Qty</th>
                                                        <th width="30%">Product</th>
                                                        <th width="15%" class="text-right">Price</th>
                                                        <th width="15%" class="text-right">Subtotal</th>
                                                        <th width="25%">Variation</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->items as $item)
                                                        <tr class="align-middle">
                                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge badge-primary">{{ $item->quantity }}</span>
                                                            </td>
                                                            <td>
                                                                <strong>{{ $item->product->name }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                {{ showAmount($item->product->final_price) }}
                                                            </td>
                                                            <td class="text-right font-weight-bold">
                                                                {{ showAmount($item->total) }}
                                                            </td>
                                                            <td>
                                                                @if ($item->variation)
                                                                    @foreach ($item->variation->attributeValues as $attrValue)
                                                                        <small class="d-block">
                                                                            <strong>{{ $attrValue->attribute->name }}:</strong>
                                                                            <span
                                                                                class="badge badge-light">{{ $attrValue->value }}</span>
                                                                        </small>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold mb-3">
                                            <i class="fas fa-credit-card"></i> Payment Method
                                        </h5>
                                        <div class="card border-left-info border-0"
                                            style="border-top: 4px solid #17a2b8 !important;">
                                            <div class="card-body">
                                                <p class="text-info font-weight-bold mb-0">
                                                    <i class="fas fa-money-bill-wave"></i> Cash On Delivery
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold mb-3">
                                            <i class="fas fa-calculator"></i> Order Summary
                                        </h5>
                                        <div class="card border-0" style="border-top: 4px solid #007bff !important;">
                                            <div class="card-body p-4">
                                                <div class="row align-items-center mb-3 pb-3 border-bottom">
                                                    <div class="col-6">
                                                        <p class="text-muted mb-0">
                                                            <i class="fas fa-boxes text-secondary"></i> Sub Total
                                                        </p>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <p class="mb-0 font-weight-bold">
                                                            {{ showAmount($order->subtotal) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if ($order->discount > 0)
                                                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                                                        <div class="col-6">
                                                            <p class="text-danger mb-0">
                                                                <i class="fas fa-tag"></i> Discount
                                                            </p>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <p class="mb-0 font-weight-bold text-danger">
                                                                - {{ showAmount($order->discount) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($order->delivery_charge > 0)
                                                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                                                        <div class="col-6">
                                                            <p class="text-success mb-0">
                                                                <i class="fas fa-truck"></i> Delivery Charge
                                                            </p>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <p class="mb-0 font-weight-bold text-success">
                                                                + {{ showAmount($order->delivery_charge) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($order->tax > 0)
                                                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                                                        <div class="col-6">
                                                            <p class="text-info mb-0">
                                                                <i class="fas fa-receipt"></i> VAT/Tax
                                                            </p>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <p class="mb-0 font-weight-bold text-info">
                                                                + {{ showAmount($order->tax) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row align-items-center mt-4">
                                                    <div class="col-6">
                                                        <p class="text-dark font-weight-bold mb-0"
                                                            style="font-size: 1.1rem;">
                                                            <i class="fas fa-money-bill-wave text-primary"></i> Total
                                                            Amount
                                                        </p>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <p class="mb-0"
                                                            style="font-size: 1.5rem; color: #007bff; font-weight: 900;">
                                                            {{ showAmount($order->total) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-print">
                                    <div class="col-12">
                                        <div class="btn-group" role="group">
                                            <a href="javascript:void(0)" id="printButton"
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                            <a href="{{ route('admin.invoice.download', $order->id) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-file-pdf"></i> Download Invoice
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
        const btn = document.getElementById('copy-order-id-btn');
        btn.addEventListener('click', function() {
            const text = document.getElementById('order-id').innerText;
            navigator.clipboard.writeText(text).then(function() {
                btn.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-copy"></i>';
                }, 1500);
            });
        });
    </script>
@endsection
