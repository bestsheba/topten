@extends('admin.layouts.app')

@section('title')
    Affiliate Users
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Affiliate Users',
        'page' => 'Affiliate Users',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Affiliate Users</h3>
                            <div class="card-tools">
                                <form action="{{ route('admin.affiliate-users.index') }}" class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
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
                                    @forelse ($affiliateUsers as $affiliate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $affiliate->name }}</td>
                                            <td>{{ $affiliate->address?->phone_number }}</td>
                                            <td>{{ $affiliate->email }}</td>
                                            <td>{{ $affiliate->address?->address }}</td>
                                            <td>
                                                <span class="badge badge-success">Affiliate</span>
                                            </td>
                                            <td>
                                                <!-- Example: affiliate view/delete 
                                                <a href="#" class="btn btn-info mr-2">View</a>
                                                -->
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12">No Data ....</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $affiliateUsers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
