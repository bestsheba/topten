<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
@vite(['resources/assets/admin/js/adminlte.js'])
@livewireScripts
@yield('scripts')

<!-- Toastr -->
<x-toastr />
