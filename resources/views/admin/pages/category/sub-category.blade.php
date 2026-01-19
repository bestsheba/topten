@extends('admin.layouts.app')

@section('title')
    Sub Category
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Sub Category',
        'page' => 'Sub Category',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @if (request()->routeIs('admin.sub-category.index'))
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Sub Category
                                </h3>
                                <div class="card-tools d-flex align-items-center">
                                    <a href="{{ route('admin.sub-category.create', ['category' => request('category')]) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class="fas fa-plus"></i> New Sub Category
                                    </a>
                                    <form
                                        action="{{ route('admin.sub-category.index', ['category' => request('category')]) }}"
                                        class="input-group input-group-sm" style="width: 150px;">
                                        <input type="hidden" value="{{ request('category') }}" name="category" readonly>
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
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sub_categories as $sub_category_item)
                                            <tr>
                                                <td>
                                                    {{ $sub_category_item->id }}
                                                </td>
                                                <td>
                                                    <img class="rounded img-fluid" width="50" height="50"
                                                        src="{{ $sub_category_item->picture_url }}"
                                                        alt="{{ $sub_category_item->name }}">
                                                </td>
                                                <td>
                                                    {{ $sub_category_item->name }}
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ route('admin.category.index', ['keyword' => $sub_category_item->category?->name]) }}">
                                                        {{ $sub_category_item->category?->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($sub_category_item->is_active)
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
                                                        <a href="{{ route('admin.sub-category.edit', $sub_category_item->id) }}"
                                                            class="btn btn-info mr-2">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.sub-category.destroy', [$sub_category_item->id, 'category' => request('category')]) }}"
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
                            {{ $sub_categories->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
                @if (request()->routeIs('admin.sub-category.create') || request()->routeIs('admin.sub-category.edit'))
                    <div class="col-md-12">
                        <div class="card card-primary">
                            @if (isset($sub_category))
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Update
                                    </h3>
                                </div>
                                <form action="{{ route('admin.sub-category.update', $sub_category->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="category">
                                                Select Category
                                            </label>
                                            <select name="category" id="category"
                                                class="form-control @error('category') is-invalid @enderror">
                                                @foreach ($categories as $category)
                                                    <option @selected(old('category', $sub_category->category_id) == $category->id) value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="name">
                                                Name
                                            </label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" value="{{ old('name', $sub_category->name) }}">
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
                                                <img src="{{ $sub_category->picture_url }}"
                                                    alt="{{ $sub_category->name }}" class="img-fluid rounded"
                                                    width="70" height="70">
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
                                                @checked($sub_category->is_active)>
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
                                <form action="{{ route('admin.sub-category.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="category">
                                                Select Category
                                            </label>
                                            <select name="category" id="category"
                                                class="form-control @error('category') is-invalid @enderror">
                                                @foreach ($categories as $category)
                                                    <option @selected(old('category') == $category->id) value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <span class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
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
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2-bootstrap.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#category').select2({
                theme: "bootstrap"
            });
        });
    </script>
@endsection
