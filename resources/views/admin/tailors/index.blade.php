@extends('admin.layouts.app')

@section('title', 'Tailor Management')

@section('page') 
<div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Tailor List</h4>

            <a href="{{ route('admin.tailors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Tailor
            </a>
        </div>
         <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">SL</th>
                            <th>Tailor Name</th>
                            <th style="width: 180px;" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tailors as $tailor)
                            <tr>
                                <td class="text-center fw-semibold">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="fw-medium">{{ $tailor->name }}</span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.tailors.edit', $tailor) }}"
                                           class="btn btn-sm btn-outline-warning mr-2">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.tailors.destroy', $tailor) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this tailor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <div class="mb-2 fs-5">No Tailors Found</div>
                                    <a href="{{ route('admin.tailors.create') }}" class="btn btn-sm btn-primary">
                                        Add First Tailor
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Pagination --}}
        @if ($tailors->hasPages())
            <div class="card-footer bg-white">
                {{ $tailors->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
