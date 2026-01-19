@extends('admin.layouts.app')
@section('title')
    Tailor Create
@endsection
@section('page')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">Add Tailor</div>
            <div class="card-body">

                <form method="POST" action="{{ route('admin.tailors.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tailor Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.tailors.index') }}" class="btn btn-secondary">
                        Back
                    </a>
                </form>

            </div>
        </div>
    </div>
@endsection
