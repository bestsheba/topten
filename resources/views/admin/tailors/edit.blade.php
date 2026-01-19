@extends('admin.layouts.app')
@section('title')
    Tailor Create
@endsection

@section('page')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Edit Tailor</div>
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.tailors.update', $tailor) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tailor Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ $tailor->name }}">
                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.tailors.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </form>

        </div>
    </div>
</div>
@endsection
