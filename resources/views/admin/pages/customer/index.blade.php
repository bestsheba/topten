@extends('admin.layouts.app')

@section('title')
    Customer
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Customer',
        'page' => 'Customer',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @if (request()->routeIs('admin.customers.index'))
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Customer
                                </h3>
                                <div class="card-tools d-flex align-items-center">
                                    <form action="{{ route('admin.customers.index') }}"
                                        class="input-group input-group-sm mr-3" style="width: 150px;">
                                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                                            class="form-control float-right" placeholder="Search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
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
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customers as $brand_item)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $brand_item->name }}
                                                </td>
                                                <td>
                                                    {{ $brand_item->address?->phone_number }}
                                                </td>
                                                <td>
                                                    {{ $brand_item->email }}
                                                </td>
                                                <td>
                                                    {{ $brand_item->address?->address }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">Active</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex items-align-center">
                                                        <a href="{{ route('admin.customers.edit', $brand_item->id) }}"
                                                            class="btn btn-info btn-sm mr-2">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.customers.destroy', $brand_item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this customer?')"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    No Data ....
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $customers->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
