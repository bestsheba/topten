@extends('admin.layouts.app')

@section('title')
    Roles
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Roles',
        'page' => 'Roles',
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
                                Roles
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.role.create') }}" class="btn btn-primary">
                                    Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $role->name }}
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $role->permissions->count() }} Permissions</span>
                                            </td>
                                            <td>
                                                @if($role->name == 'Super Admin')
                                                    <a href="{{ route('admin.role.edit', $role->id) }}"
                                                        class="btn btn-info">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.role.destroy', $role->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Are you sure?')" type="submit"
                                                            class="btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $roles->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
