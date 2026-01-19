@extends('admin.layouts.app')

@section('title')
    Edit Role
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit Role',
        'page' => 'Edit Role',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Edit Role
                            </h3>
                        </div>
                        <form action="{{ route('admin.role.update', $role->id) }}" method="POST" class="p-4">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $role->name) }}" placeholder="Enter role name">
                                @error('name')
                                    <span class="error invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Permissions</label>
                                @error('permissions')
                                    <span class="error text-danger d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                                @foreach($permissions as $group => $group_permissions)
                                    <div class="card mt-3">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">
                                                {{ ucfirst($group) }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($group_permissions as $permission)
                                                    <div class="col-md-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                class="custom-control-input"
                                                                id="permission_{{ $permission->id }}"
                                                                name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                                {{ ucfirst($permission->name) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
