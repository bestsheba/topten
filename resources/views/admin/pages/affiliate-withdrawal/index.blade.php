@extends('admin.layouts.app')

@section('title')
    Affiliate Withdrawals
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Affiliate Withdrawals
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="admin">
                                Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Affiliate Withdrawals
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
                                Withdrawal Requests
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <form action="{{ route('admin.affiliate-withdrawal.index') }}" class="input-group input-group-sm mr-3"
                                    style="width: 200px;">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        @foreach($statuses as $key => $status)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                </form>
                                <form action="{{ route('admin.affiliate-withdrawal.index') }}" class="input-group input-group-sm mr-3"
                                    style="width: 200px;">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                        class="form-control float-right" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Affiliate</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Account Info</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($withdrawals as $withdrawal)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">
                                                    {{ $withdrawal->user->name }}
                                                </div>
                                                <div class="text-muted">
                                                    {{ $withdrawal->user->email }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">
                                                    {{ number_format($withdrawal->amount, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $withdrawal->method ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 150px;" title="{{ $withdrawal->account_info }}">
                                                    {{ $withdrawal->account_info ?? '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $withdrawal->status === 'pending' ? 'warning' : 
                                                    ($withdrawal->status === 'approved' ? 'success' : 'danger') 
                                                }}">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $withdrawal->created_at->format('d M, Y h:i A') }}
                                            </td>
                                            <td>
                                                <div class="d-flex items-align-center">
                                                    <a href="{{ route('admin.affiliate-withdrawal.show', $withdrawal->id) }}"
                                                        class="btn btn-info btn-sm mr-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.affiliate-withdrawal.edit', $withdrawal->id) }}"
                                                        class="btn btn-primary btn-sm mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($withdrawal->status === 'pending')
                                                        <form action="{{ route('admin.affiliate-withdrawal.destroy', $withdrawal->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                No withdrawal requests found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $withdrawals->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
