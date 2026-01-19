@extends('admin.layouts.app')

@section('title', 'Expense Types')

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Expense Types',
        'page' => 'Expenses',
    ])
@endsection

@section('page')
<section class="content">
    <div class="container-fluid">

        {{-- Alerts --}}
        {{-- @include('admin.partials.alerts') --}}

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Expense Type List</h3>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus"></i> Add Type
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Name</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-info"
                                        data-toggle="modal"
                                        data-target="#editModal{{ $type->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.expense-types.destroy', $type) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            onclick="return confirm('Delete this expense type?')"
                                            class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $type->id }}">
                                <div class="modal-dialog">
                                    <form method="POST"
                                          action="{{ route('admin.expense-types.update', $type) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Expense Type</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text"
                                                           name="name"
                                                           class="form-control"
                                                           value="{{ $type->name }}"
                                                           required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button class="btn btn-primary">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No expense types found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Add Modal --}}
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.expense-types.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Type</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="e.g. Transport, Rent"
                               required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
