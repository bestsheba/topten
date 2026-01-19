@extends('admin.layouts.app')

@section('title')
    Admins
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Admins',
        'page' => 'Admins',
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
                                Admins
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.admin.create') }}" class="btn btn-primary">
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
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $key => $admin)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $admin->name }}
                                            </td>
                                            <td>
                                                {{ $admin->email }}
                                            </td>
                                            <td>
                                                @foreach($admin->roles as $role)
                                                    <span class="badge badge-info">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.admin.edit', $admin->id) }}"
                                                    class="btn btn-info">
                                                    Edit
                                                </a>
                                                @if(!$admin->hasRole('Super Admin'))
                                                    <form action="{{ route('admin.admin.destroy', $admin->id) }}"
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
                        {{ $admins->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
