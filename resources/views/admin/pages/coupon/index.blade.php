@extends('admin.layouts.app')

@section('title')
    Coupons
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Coupons',
        'page' => 'Coupons',
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
                                Coupons
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">
                                    Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Discount</th>
                                        <th>Start Date</th>
                                        <th>Expired Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $key => $coupon)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $coupon->title }}
                                            </td>
                                            <td>
                                                {{ $coupon->code }}
                                            </td>
                                            <td>
                                                @if($coupon->discount_type == 'amount')
                                                    ৳{{ number_format($coupon->discount, 2) }}
                                                @else
                                                    {{ $coupon->discount }}%
                                                @endif
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($coupon->start_date)) }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($coupon->expired_date)) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                                    class="btn btn-info">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.coupon.destroy', $coupon->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')" type="submit"
                                                        class="btn btn-danger">
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
                        {{ $coupons->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
