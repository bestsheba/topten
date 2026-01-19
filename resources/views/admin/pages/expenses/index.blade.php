@extends('admin.layouts.app')

@section('title')
    Expenses
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Expenses',
        'page' => 'Expenses',
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
                                Expenses
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <form action="{{ route('admin.expenses.index') }}" class="input-group input-group-sm mr-3"
                                    style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                        class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                                <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Expense Type</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->date }}</td>
                                            <td>{{ $expense->expense_name }}</td>
                                            <td>{{ $expense->expenseType->name }}</td>
                                            <td>{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                <a href="{{ route('admin.expenses.edit', $expense) }}"
                                                    class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('admin.expenses.destroy', $expense) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')"
                                                        class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $expenses->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
