@extends('admin.layouts.app')

@section('title')
    Edit Withdrawal Request
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Edit Withdrawal Request
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
                            Edit Request
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
                                Update Withdrawal Request
                            </h3>
                        </div>
                        <form action="{{ route('admin.affiliate-withdrawal.update', $affiliateWithdrawal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Affiliate Information</label>
                                            <div class="form-control-plaintext">
                                                <strong>{{ $affiliateWithdrawal->user->name }}</strong><br>
                                                <small class="text-muted">{{ $affiliateWithdrawal->user->email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Request Amount</label>
                                            <div class="form-control-plaintext">
                                                <strong class="text-primary">{{ number_format($affiliateWithdrawal->amount, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Withdrawal Method</label>
                                            <div class="form-control-plaintext">{{ $affiliateWithdrawal->method ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Information</label>
                                            <div class="form-control-plaintext">{{ $affiliateWithdrawal->account_info ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{ $key }}" {{ old('status', $affiliateWithdrawal->status) == $key ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Request Date</label>
                                            <div class="form-control-plaintext">{{ $affiliateWithdrawal->created_at->format('d M, Y h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="note">Admin Note</label>
                                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" 
                                              rows="4" placeholder="Add any notes about this withdrawal request...">{{ old('note', $affiliateWithdrawal->note) }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        This note will be visible to the affiliate (optional).
                                    </small>
                                </div>

                                @if($affiliateWithdrawal->status === 'pending')
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Important:</strong> If you approve this withdrawal, the amount will be deducted from the affiliate's wallet balance.
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Status
                                </button>
                                <a href="{{ route('admin.affiliate-withdrawal.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Request Summary
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Request ID:</th>
                                    <td>#{{ $affiliateWithdrawal->id }}</td>
                                </tr>
                                <tr>
                                    <th>Current Status:</th>
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
                                    <th>Last Updated:</th>
                                    <td>{{ $affiliateWithdrawal->updated_at->format('d M, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.affiliate-withdrawal.show', $affiliateWithdrawal->id) }}" 
                               class="btn btn-info btn-block mb-2">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('admin.affiliate-withdrawal.index') }}" 
                               class="btn btn-secondary btn-block">
                                <i class="fas fa-list"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
