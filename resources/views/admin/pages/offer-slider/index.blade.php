@extends('admin.layouts.app')

@section('title')
    Offer Sliders
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Offer Sliders',
        'page' => 'Offer Sliders',
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
                                Offer Sliders
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.offer-slider.create') }}" class="btn btn-primary">
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
                                        <th>Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $key => $slider)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $slider->title }}</td>
                                            <td>
                                                @if (isImageUrl($slider->image_url))
                                                    <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                                                        style="height: 80px">
                                                @else
                                                    <a href="{{ $slider->image_url }}">View file</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ $slider->link }}">
                                                    {{ $slider->link ?? '-' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.offer-slider.edit', $slider->id) }}"
                                                    class="btn btn-info">Edit</a>
                                                <form action="{{ route('admin.offer-slider.destroy', $slider->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')" type="submit"
                                                        class="btn btn-danger">Delete</button>
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
