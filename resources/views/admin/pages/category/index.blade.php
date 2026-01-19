@extends('admin.layouts.app')

@section('title')
    Category
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Category',
        'page' => 'Category',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @if (request()->routeIs('admin.category.index'))
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Category
                                </h3>
                                <div class="card-tools d-md-flex align-items-center">
                                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary btn-sm mr-md-2 mb-2 mb-md-0">
                                        <i class="fas fa-plus"></i> New Category
                                    </a>
                                    <form action="{{ route('admin.category.index') }}" class="input-group input-group-sm"
                                        style="width: 150px;">
                                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                                            class="form-control float-right" placeholder="Search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Picture</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category_item)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <img class="rounded img-fluid" width="50" height="50"
                                                        src="{{ $category_item->picture_url }}"
                                                        alt="{{ $category_item->name }}">
                                                </td>
                                                <td>
                                                    {{ $category_item->name }}
                                                </td>
                                                <td>
                                                    @if ($category_item->is_active)
                                                        <span class="badge badge-success">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            Disabled
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex items-align-center">
                                                        <a href="{{ route('admin.sub-category.index', ['category' => $category_item->id]) }}"
                                                            class="btn btn-secondary mr-2">
                                                            Sub Category
                                                        </a>
                                                        <a href="{{ route('admin.category.edit', $category_item->id) }}"
                                                            class="btn btn-info mr-2">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.category.destroy', $category_item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                                class="btn btn-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    No Data ....
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $categories->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
                @if (request()->routeIs('admin.category.edit') || request()->routeIs('admin.category.create'))
                    <div class="col-md-12">
                        <div class="card card-primary">
                            @if (isset($category))
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Update
                                    </h3>
                                </div>
                                <form action="{{ route('admin.category.update', $category->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">
                                                Name
                                            </label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" value="{{ old('name', $category->name) }}">
                                            @error('name')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="picture">
                                                Old Picture
                                            </label>
                                            <div>
                                                <img src="{{ $category->picture_url }}" alt="{{ $category->name }}"
                                                    class="img-fluid rounded" width="70" height="70">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="picture">
                                                Select Picture
                                            </label>
                                            <input type="file" name="picture"
                                                class="form-control @error('picture') is-invalid @enderror"
                                                style="line-height: 1.2" accept="image/*">
                                            @error('picture')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="active" class="form-check-input" id="active"
                                                @checked($category->is_active)>
                                            <label class="form-check-label" for="active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Add New
                                    </h3>
                                </div>
                                <form action="{{ route('admin.category.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">
                                                Name
                                            </label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="picture">
                                                Select Picture
                                            </label>
                                            <input type="file" name="picture"
                                                class="form-control @error('picture') is-invalid @enderror"
                                                style="line-height: 1.2" accept="image/*">
                                            @error('picture')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="active" class="form-check-input"
                                                id="active">
                                            <label class="form-check-label" for="active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
