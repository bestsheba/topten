@extends('admin.layouts.app')

@section('title')
    Blog
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Blog',
        'page' => 'Blog',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Blog
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                                    Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($blogs as $blog)
                                        <tr>
                                            <td>
                                                {{ $blog->id }}
                                            </td>
                                            <td>
                                                <img src="{{ $blog->thumbnail_url }}" alt="" width="120px"
                                                    height="100px">
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ $blog->title }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex items-align-center">
                                                    <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                                        class="btn btn-info mr-2">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.blog.destroy', $blog->id) }}"
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
                        {{ $blogs->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
