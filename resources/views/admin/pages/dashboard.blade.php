@extends('admin.layouts.app')

@section('title')
    Dashboard Page
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Dashboard Page',
        'page' => 'Dashboard Page',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <x-admin.dashboard-card title="Orders" :count="$total_orders" background="mycard1"
                    icon='<i class="fas fa-shopping-cart text-white" style="font-size: 4rem; opacity: 0.7;"></i>'
                    url="{{ route('admin.order.index') }}" />
                <x-admin.dashboard-card title="Sales" :count="$sales" background="mycard2"
                    icon='<i class="fas fa-dollar-sign text-white" style="font-size: 4rem; opacity: 0.7;"></i>'
                    url="{{ route('admin.order.index') }}" />
                <x-admin.dashboard-card title="Products" :count="$total_products" background="mycard3"
                    icon='<i class="fab fa-product-hunt text-white" style="font-size: 4rem; opacity: 0.7;"></i>'
                    url="{{ route('admin.product.index') }}" />
                <x-admin.dashboard-card title="Customers" :count="$total_customers" background="mycard4"
                    icon='<i class="fas fa-users text-white" style="font-size: 4rem; opacity: 0.7;"></i>'
                    url="{{ route('admin.customers.index') }}" />
            </div>

            <!-- Circular Stats Row -->
            <div class="row">
                @foreach ($order_status_with_orders as $status)
                    <x-admin.circle-stat title="{{ $status['status'] }}" count="{{ $status['order_count'] }}"
                        color="{{ $status['color'] }}" />
                @endforeach
            </div>
            <br>
            <div class="w-100 border bg-white">
                <div class="flex justify-content-end m-2 mb-0">
                    <form action="{{ route('admin.dashboard') }}">
                        <select onchange="this.form.submit()" name="stats" id="stats"
                            class="form-control col12 col-md-4">
                            <option {{ request('stats') == 'this_week' ? 'selected' : '' }} value="this_week">
                                This Week
                            </option>
                            <option {{ request('stats') == 'this_month' ? 'selected' : '' }} value="this_month">
                                This Month
                            </option>
                            <option {{ request('stats') == 'last_month' ? 'selected' : '' }} value="last_month">
                                Last Month
                            </option>
                            <option {{ request('stats') == 'last_6_months' ? 'selected' : '' }} value="last_6_months">
                                Last 6 Months
                            </option>
                            <option {{ request('stats') == 'last_year' ? 'selected' : '' }} value="last_year">
                                Last Year
                            </option>
                        </select>
                    </form>
                </div>
                <canvas id="cart" height="100"></canvas>
            </div>
            <br>
            <br>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .customPadding {
            padding: 25px 40px 30px;
            position: relative;
            z-index: 9999;
        }

        @media (max-width: 768px) {
            .customPadding {
                padding: 0px;
                position: relative;
                z-index: 9999;
            }

            .mycard1::after,
            .mycard2::after,
            .mycard3::after,
            .mycard4::after,
            .mycard5::after,
            .mycard6::after {
                width: 190px !important;
            }

            #cart {
                min-height: 400px;
            }

        }

        .mycard1 {
            background-image: -webkit-gradient(linear, left top, right top, from(#f85108), to(#f4ad3c));
            background-image: -webkit-linear-gradient(left, #f85108, #f4ad3c);
            background-image: -o-linear-gradient(left, #f85108, #f4ad3c);
            background-image: linear-gradient(to right, #f85108, #f4ad3c);
        }

        .mycard1 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard1::after {
            background: #f85108;
        }

        .mycard1::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        /* //card two  */
        .mycard2 {
            background-image: -webkit-gradient(linear, left top, right top, from(#047edf), to(#0bb9fa));
            background-image: -webkit-linear-gradient(left, #047edf, #0bb9fa);
            background-image: -o-linear-gradient(left, #047edf, #0bb9fa);
            background-image: linear-gradient(to right, #047edf, #0bb9fa);
        }

        .mycard2 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard2::after {
            background: #047edf;
        }

        .mycard2::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        /* card 3 */
        .mycard3 {
            background-image: -webkit-gradient(linear, left top, right top, from(#0fa49b), to(#03dbce));
            background-image: -webkit-linear-gradient(left, #0fa49b, #03dbce);
            background-image: -o-linear-gradient(left, #0fa49b, #03dbce);
            background-image: linear-gradient(to right, #0fa49b, #03dbce);
        }

        .mycard3 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard3::after {
            background: #0fa49b;
        }

        .mycard3::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        /* card 4 */
        .mycard4 {
            background-image: -webkit-gradient(linear, left top, right top, from(#5a49e9), to(#7a6cf0));
            background-image: -webkit-linear-gradient(left, #5a49e9, #7a6cf0);
            background-image: -o-linear-gradient(left, #5a49e9, #7a6cf0);
            background-image: linear-gradient(to right, #5a49e9, #7a6cf0);
        }

        .mycard4 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard4::after {
            background: #352d7b;
        }

        .mycard4::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        /* card 5 */
        .mycard5 {
            background-image: -webkit-gradient(linear, left top, right top, from(#cf0633), to(#f96079));
            background-image: -webkit-linear-gradient(left, #cf0633, #f96079);
            background-image: -o-linear-gradient(left, #cf0633, #f96079);
            background-image: linear-gradient(to right, #cf0633, #f96079);
        }

        .mycard5 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard5::after {
            background: #cf0633;
        }

        .mycard5::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        /* card 6 */
        .mycard6 {
            background-image: -webkit-gradient(linear, left top, right top, from(#129021), to(#1ed41e));
            background-image: -webkit-linear-gradient(left, #129021, #1ed41e);
            background-image: -o-linear-gradient(left, #129021, #1ed41e);
            background-image: linear-gradient(to right, #129021, #1ed41e);
        }

        .mycard6 {
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        .mycard6::after {
            background: #129021;
        }

        .mycard6::after {
            position: absolute;
            content: " ";
            width: 268px;
            height: 500px;
            top: -100px;
            right: -50px;
            -webkit-transform: rotate(28deg);
            -ms-transform: rotate(28deg);
            transform: rotate(28deg);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .display-4 {
            font-size: 3rem;
            font-weight: 700;
        }

        .btn-light {
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
        }

        .circular-chart {
            max-width: 120px;
            margin: 0 auto;
        }

        .circle-bg {
            fill: none;
            stroke-width: 3;
        }

        .circle {
            fill: none;
            stroke-width: 3;
        }

        .count {
            font-family: sans-serif;
            font-size: 10px;
            text-anchor: middle;
            fill: #333;
            font-weight: bold;
        }
    </style>
@endsection

@section('script')
    @vite(['resources/assets/admin/js/chartjs.js'])

    <script>
        const labels = @json($stats[0]);
        const data = @json($stats[1]);

        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('cart').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line', // also try bar or other graph types

                // The data for our dataset
                data: {
                    labels: labels,
                    // Information about the dataset
                    datasets: [{
                        label: "This {{ str_replace('_', ' ', request('stats')) }} Order Statistic",
                        backgroundColor: 'lightblue',
                        borderColor: 'royalblue',
                        data: data,
                    }]
                },

                // Configuration options
                options: {
                    layout: {
                        padding: 10,
                    },
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: "This {{ str_replace('_', ' ', request('stats')) }} Order Statistic"
                    },
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: "This {{ str_replace('_', ' ', request('stats')) }} Order Statistic"
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: "This {{ str_replace('_', ' ', request('stats')) }} Order Statistic"
                            }
                        }]
                    }
                }
            });

        });
    </script>
@endsection
