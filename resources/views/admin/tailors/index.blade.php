@extends('admin.layouts.app')
@section('title')
    Tailor Management
@endsection
@section('page')
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h4>Tailor List</h4>
            <a href="{{ route('admin.tailors.create') }}" class="btn btn-primary">
                Add Tailor
            </a>
        </div>
        {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tailor Name</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tailors as $tailor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tailor->name }}</td>
                        <td>
                            <a href="{{ route('admin.tailors.edit', $tailor) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('admin.tailors.destroy', $tailor) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this tailor?')" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No Tailors Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $tailors->links('pagination::bootstrap-5') }}

    </div>
@endsection
