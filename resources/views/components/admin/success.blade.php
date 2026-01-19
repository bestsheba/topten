@session('success')
    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
        {{ session()->get('success') }}
    </div>
@endsession
