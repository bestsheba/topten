@extends('admin.layouts.app')

@section('title', 'Measurement Fields')

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Measurement Fields',
        'page' => 'Measurements / Fields',
    ])
@endsection
@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Measurement Fields (Garment-wise)</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                            <i class="fas fa-plus"></i> Add Field
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @forelse($garments as $garment)
                        <h5 class="mt-4">{{ $garment->name }}</h5>

                        <table class="table table-bordered table-hover mt-2">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>Label</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($garment->measurementFields as $field)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $field->label }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#editModal{{ $field->id }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.measurement-fields.destroy', $field) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this field?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $field->id }}">
                                        <div class="modal-dialog">
                                            <form method="POST"
                                                action="{{ route('admin.measurement-fields.update', $field) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Measurement Field</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Label</label>
                                                            <input type="text" name="label" class="form-control"
                                                                value="{{ $field->label }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No measurement fields found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @empty
                        <p class="text-muted">No garment types found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.measurement-fields.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Measurement Field</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Garment Type <span class="text-danger">*</span></label>
                            <select name="garment_type_id" class="form-control" required>
                                <option value="">Select Garment</option>
                                @foreach ($garments as $garment)
                                    <option value="{{ $garment->id }}">{{ $garment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Label <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" placeholder="Chest, Waist" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
