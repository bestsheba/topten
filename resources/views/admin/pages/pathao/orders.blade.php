@extends('admin.layouts.app')

@section('title')
    Pathao Orders
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Pathao Orders
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="admin">
                                Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.pathao.settings') }}">Pathao</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Orders
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Pathao Orders
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.pathao.settings') }}" class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Order ID</th>
                                        <th>Consignment ID</th>
                                        <th>Recipient</th>
                                        <th>Amount to Collect</th>
                                        <th>Delivery Fee</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        @if($order->pathaoOrder)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('admin.order.show', $order->id) }}">
                                                        {{ $order->hashed_id }}
                                                    </a>
                                                </td>
                                                <td>{{ $order->pathaoOrder->consignment_id ?? '-' }}</td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $order->pathaoOrder->recipient_name }}</div>
                                                    <div class="text-muted">{{ $order->pathaoOrder->recipient_phone }}</div>
                                                </td>
                                                <td>{{ showAmount($order->pathaoOrder->amount_to_collect) }}</td>
                                                <td>{{ showAmount($order->pathaoOrder->delivery_fee ?? 0) }}</td>
                                                <td>{!! $order->pathaoOrder->status_badge !!}</td>
                                                <td>{{ $order->pathaoOrder->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Pathao orders found</td>
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
@endsection
