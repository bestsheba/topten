@extends('admin.layouts.app')

@section('title')
    Custom Pages
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Custom Pages',
        'page' => 'Custom Pages',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h3 class="card-title mb-2 mb-md-0">All Custom Pages</h3>
                            <div class="card-tools d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                <a href="{{ route('admin.custom-page-group.index') }}" class="btn btn-outline-secondary mr-0 mr-md-2 mb-2 mb-md-0">
                                    <i class="fas fa-folder-open mr-1"></i> Manage Groups
                                </a>
                                <a href="{{ route('admin.custom-page.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i> Create Page
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50" class="text-center">#</th>
                                        <th>Title</th>
                                        <th>Group</th>
                                        <th>Slug</th>
                                        <th width="150" class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $pageCounter = 1;
                                        $pages = \App\Models\CustomPage::with('group')->orderBy('created_at', 'desc')->get(); 
                                    @endphp
                                    @foreach($pages as $page)
                                        <tr>
                                            <td class="text-center">{{ $pageCounter++ }}</td>
                                            <td>
                                                <div class="d-md-none font-weight-bold">Title:</div>
                                                {{ $page->title }}
                                            </td>
                                            <td>
                                                <div class="d-md-none font-weight-bold">Group:</div>
                                                {{ $page->group->name ?? 'Uncategorized' }}
                                            </td>
                                            <td>
                                                <div class="d-md-none font-weight-bold">Slug:</div>
                                                {{ $page->slug }}
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.custom-page.edit', $page->id) }}" class="btn btn-sm btn-info mr-1">
                                                        <i class="fas fa-edit"></i>
                                                        <span class="d-none d-md-inline">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.custom-page.destroy', $page->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                            <span class="d-none d-md-inline">Delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($pages->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                                <p class="mb-0">No custom pages have been created yet.</p>
                                                <small class="text-muted">Click "Create Page" to get started.</small>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
    @media (max-width: 767.98px) {
        .table-responsive {
            overflow-x: auto;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
        .table tbody td {
            display: block;
            text-align: right;
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }
        .table tbody td:before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
        }
        .table tbody td:last-child {
            border-bottom: none;
        }
        .btn-group {
            display: flex;
            justify-content: flex-end;
        }
        .btn-group .btn {
            margin: 5px;
        }
    }
</style>
@endsection
