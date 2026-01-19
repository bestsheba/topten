@extends('admin.layouts.app')

@section('title')
    View Withdrawal Request
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        View Withdrawal Request
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.affiliate-withdrawal.index') }}">
                                Affiliate Withdrawals
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            View Request
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Withdrawal Request Details
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">Request ID:</th>
                                            <td>#{{ $affiliateWithdrawal->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Affiliate Name:</th>
                                            <td>{{ $affiliateWithdrawal->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $affiliateWithdrawal->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount:</th>
                                            <td class="font-weight-bold text-primary">
                                                {{ number_format($affiliateWithdrawal->amount, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Method:</th>
                                            <td>{{ $affiliateWithdrawal->method ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Info:</th>
                                            <td>{{ $affiliateWithdrawal->account_info ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">Status:</th>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $affiliateWithdrawal->status === 'pending' ? 'warning' : 
                                                    ($affiliateWithdrawal->status === 'approved' ? 'success' : 'danger') 
                                                }}">
                                                    {{ ucfirst($affiliateWithdrawal->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Request Date:</th>
                                            <td>{{ $affiliateWithdrawal->created_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated Date:</th>
                                            <td>{{ $affiliateWithdrawal->updated_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                        @if($affiliateWithdrawal->note)
                                            <tr>
                                                <th>Admin Note:</th>
                                                <td>{{ $affiliateWithdrawal->note }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.affiliate-withdrawal.edit', $affiliateWithdrawal->id) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Status
                            </a>
                            <a href="{{ route('admin.affiliate-withdrawal.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($affiliateWithdrawal->status === 'pending')
                                <form action="{{ route('admin.affiliate-withdrawal.update', $affiliateWithdrawal->id) }}" 
                                      method="POST" class="mb-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success btn-block" 
                                            onclick="return confirm('Approve this withdrawal request?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.affiliate-withdrawal.update', $affiliateWithdrawal->id) }}" 
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-block" 
                                            onclick="return confirm('Reject this withdrawal request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    This withdrawal request has already been {{ $affiliateWithdrawal->status }}.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
