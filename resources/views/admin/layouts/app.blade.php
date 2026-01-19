<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title') - {{ $settings?->website_name }}
    </title>

    <x-favicon-icon />

    @include('admin.layouts.links')
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.layouts.header')
        <!-- Navbar End -->

        <!-- Main Sidebar Container -->
        @include('admin.layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('page-header')

            <!-- Main content -->
            <section class="content">

                @yield('page')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('admin.layouts.footer')
    </div>
    <!-- ./wrapper -->
    @include('admin.layouts.scripts')
    @yield('script')
    @stack('script')
</body>

</html>
