@extends('admin.layouts.app')

@section('title')
    sliders
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'sliders',
        'page' => 'sliders',
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
                                sliders
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
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
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $key => $slider)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $slider->title }}</td>
                                            <td>{{ $slider->description }}</td>
                                            <td><img src="{{ asset($slider->image) }}" width="100" alt="Slider Image">
                                            </td>
                                            <td>
                                                <a href="{{ $slider->link }}">
                                                    {{ $slider->link ?? '-' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.sliders.edit', $slider->id) }}"
                                                    class="btn btn-info">Edit</a>
                                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
