@extends('admin.layouts.app')

@section('title')
    Steadfast Settings
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Steadfast Settings
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="admin">
                                Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Steadfast Settings
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Steadfast API Configuration
                            </h3>
                        </div>
                        <form action="{{ route('admin.steadfast.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="base_url">Base URL</label>
                                            <input type="url" name="base_url" id="base_url"
                                                class="form-control @error('base_url') is-invalid @enderror"
                                                value="{{ old('base_url', $steadfastSettings->base_url ?? 'https://portal.packzy.com/api/v1') }}">
                                            @error('base_url')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="api_key">API Key</label>
                                            <input type="text" name="api_key" id="api_key"
                                                class="form-control @error('api_key') is-invalid @enderror"
                                                value="{{ old('api_key', $steadfastSettings->api_key ?? '') }}">
                                            @error('api_key')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secret_key">Secret Key</label>
                                            <input type="password" name="secret_key" id="secret_key"
                                                class="form-control @error('secret_key') is-invalid @enderror"
                                                value="{{ old('secret_key', $steadfastSettings->secret_key ?? '') }}">
                                            @error('secret_key')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                                @if ($steadfastSettings)
                                    <button type="button" class="btn btn-success ml-2" onclick="testConnection(event)">
                                        <i class="fas fa-plug"></i> Test Connection
                                    </button>
                                @endif
                                <a href="{{ route('admin.steadfast.orders') }}" class="btn btn-info ml-2">
                                    <i class="fas fa-shopping-cart"></i> View Orders
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const environmentSelect = document.getElementById('environment');
            const baseUrlInput = document.getElementById('base_url');

            environmentSelect.addEventListener('change', function() {
                if (this.value === 'sandbox') {
                    baseUrlInput.value = 'https://portal.packzy.com/api/v1';
                } else {
                    baseUrlInput.value = 'https://portal.steadfast.com.bd/api/v1';
                }
            });
        });

        function testConnection(e) {
            const button = e.currentTarget;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
            button.disabled = true;

            fetch('{{ route('admin.steadfast.test-connection') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Connection successful!');
                        // Optionally display balance if returned
                        if (data.balance) {
                            console.log('Balance:', data.balance);
                        }
                    } else {
                        alert('Connection failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Connection failed: ' + error.message);
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
    </script>
@endsection
