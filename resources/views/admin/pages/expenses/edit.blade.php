@extends('admin.layouts.app')

@section('title', 'Edit Expense')

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit Expense',
        'page' => 'Expenses',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Edit Expense</h3>
                        </div>

                        <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">

                                    {{-- Expense Type --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expense Type <span class="text-danger">*</span></label>
                                            <select name="expense_type_id"
                                                class="form-control @error('expense_type_id') is-invalid @enderror">
                                                <option value="">Select Type</option>
                                                @foreach ($expenseTypes as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ old('expense_type_id', $expense->expense_type_id) == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('expense_type_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Date --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date"
                                                class="form-control @error('date') is-invalid @enderror"
                                                value="{{ old('date', $expense->date->format('Y-m-d')) }}">
                                            @error('date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Expense Name --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expense Name <span class="text-danger">*</span></label>
                                            <input type="text" name="expense_name"
                                                class="form-control @error('expense_name') is-invalid @enderror"
                                                value="{{ old('expense_name', $expense->expense_name) }}"
                                                placeholder="Enter expense name">
                                            @error('expense_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="amount"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                value="{{ old('amount', $expense->amount) }}" placeholder="0.00">
                                            @error('amount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Notes --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                                placeholder="Optional notes">{{ old('notes', $expense->notes) }}</textarea>
                                            @error('notes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save mr-1"></i> Update Expense
                                </button>
                                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
also need crud for expense type, feild only name so don't need more page complete in one page if need use modal