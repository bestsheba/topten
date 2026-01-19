@extends('admin.layouts.app')

@section('title')
    Edit Order
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Edit Order',
        'page' => 'Edit Order',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Edit Order
                            </h3>
                        </div>
                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">
                                        Status
                                    </label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        @foreach ($statuses as $key => $status)
                                            <option value="{{ $key }}">
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2-bootstrap.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#status').select2({
                theme: "bootstrap"
            });
        });
    </script>
@endsection
