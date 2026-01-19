@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endpush

@section('title')
    {{ $page == 'payment' ? 'Payment Gateway' : 'Settings' }}
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => $page == 'payment' ? 'Payment Gateway' : 'Settings',
        'page' => $page == 'payment' ? 'Payment Gateway' : 'Settings',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                @include('admin.pages.setting.home')
                                @include('admin.pages.setting.footer')
                                @include('admin.pages.setting.charge')
                                @include('admin.pages.setting.offline-payment')
                                @include('admin.pages.setting.online-payment')
                                @include('admin.pages.setting.flash')
                                @include('admin.pages.setting.site')
                                @include('admin.pages.setting.affiliate')
                                @include('admin.pages.setting.google-login')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
