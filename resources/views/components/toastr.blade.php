<script type="module">
    import {
        Eggy
    } from "{{ asset('assets/js/eggy.js') }}";

    @if (Session::has('success'))
        await Eggy({
            title: 'Success!',
            message: "{{ Session::get('success') }}",
            // duration: 100000
        });
    @elseif (Session::has('warning'))
        await Eggy({
            title: 'Warning!',
            message: "{{ Session::get('warning') }}",
            type: 'warning',
            // duration: 100000
        });
    @elseif (Session::has('error'))
        await Eggy({
            title: 'Error!',
            message: "{{ Session::get('error') }}",
            type: 'error',
            // duration: 100000
        });
    @elseif (Session::has('info'))
        await Eggy({
            title: 'Info!',
            message: "{{ Session::get('info') }}",
            type: 'info',
            // duration: 100000
        });
    @endif
</script>
