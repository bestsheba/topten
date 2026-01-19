@extends('admin.layouts.app')

@section('title')
    Landing Pages
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Landing Pages',
        'page' => 'Landing Pages',
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
                                Landing Pages
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('admin.landing-pages.create') }}" class="btn btn-primary">
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
                                        <th>URL</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($landingPages as $key => $landingPage)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $landingPage->title }}</td>
                                            <td>
                                                <a href="{{ route('landing.page.view', $landingPage->url) }}"
                                                    target="_blank">
                                                    {{ $landingPage->url }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($landingPage->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#configModal-{{ $landingPage->id }}">
                                                    ⚙️ Config
                                                </button>
                                                <a href="{{ route('admin.landing-pages.edit', $landingPage->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                <form action="{{ route('admin.landing-pages.destroy', $landingPage->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No landing pages found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Config Modals for each Landing Page -->
    @foreach ($landingPages as $landingPage)
        <div class="modal fade" id="configModal-{{ $landingPage->id }}" tabindex="-1" role="dialog"
            aria-labelledby="configModalLabel" aria-hidden="true" style="z-index: 99999;">
            <div class="modal-dialog modal-xl" role="document" style="z-index: 10000;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="configModalLabel">
                            ⚙️ Configure: {{ $landingPage->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                        <livewire:landing-page-config-editor :landing-page="$landingPage" :key="'config-' . $landingPage->id" />
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('notify', function(e) {
            const detail = e.detail;
            const alertClass = detail.type === 'success' ? 'alert-success' :
                (detail.type === 'error' ? 'alert-danger' : 'alert-info');
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${detail.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;

            const alertContainer = document.querySelector('.content .container-fluid');
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = alertHtml;
            alertContainer.insertBefore(tempDiv.firstElementChild, alertContainer.firstChild);

            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) alert.remove();
            }, 5000);
        });
    </script>
@endsection
