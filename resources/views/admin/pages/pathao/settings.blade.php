@extends('admin.layouts.app')

@section('title')
    Pathao Settings
@endsection

@section('page')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <h1>
                        Pathao Settings
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
                            Pathao Settings
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
                                Pathao API Configuration
                            </h3>
                        </div>
                        <form action="{{ route('admin.pathao.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="environment">Environment</label>
                                            <select name="environment" id="environment" class="form-control @error('environment') is-invalid @enderror" required>
                                            <option value="sandbox" {{ old('environment', $pathaoSettings->environment ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox/Test</option>
                                            <option value="production" {{ old('environment', $pathaoSettings->environment ?? '') == 'production' ? 'selected' : '' }}>Production/Live</option>
                                            </select>
                                            @error('environment')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="base_url">Base URL</label>
                                            <input type="url" name="base_url" id="base_url" class="form-control @error('base_url') is-invalid @enderror" 
                                                   value="{{ old('base_url', $pathaoSettings->base_url ?? 'https://courier-api-sandbox.pathao.com') }}" required>
                                            @error('base_url')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_id">Client ID</label>
                                            <input type="text" name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" 
                                                   value="{{ old('client_id', $pathaoSettings->client_id ?? '7N1aMJQbWm') }}" required>
                                            @error('client_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_secret">Client Secret</label>
                                            <input type="password" name="client_secret" id="client_secret" class="form-control @error('client_secret') is-invalid @enderror" 
                                                   value="{{ old('client_secret', $pathaoSettings->client_secret ?? '') }}" required>
                                            @error('client_secret')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username (Email)</label>
                                            <input type="email" name="username" id="username" class="form-control @error('username') is-invalid @enderror" 
                                                   value="{{ old('username', $pathaoSettings->username ?? 'test@pathao.com') }}" required>
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                                   value="{{ old('password', $pathaoSettings->password ?? '') }}" required>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($pathaoSettings && $pathaoSettings->store_name)
                                    <div class="alert alert-info">
                                        <h5><i class="icon fas fa-info"></i> Store Information</h5>
                                        <strong>Store Name:</strong> {{ $pathaoSettings->store_name }}<br>
                                        <strong>Store ID:</strong> 
                                        @if($pathaoSettings->store_id)
                                            {{ $pathaoSettings->store_id }}
                                        @else
                                            <span class="text-warning">Not Set</span>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2" onclick="showStoreIdInput()">
                                                Set Store ID
                                            </button>
                                        @endif
                                        <br>
                                        <strong>Store Address:</strong> {{ $pathaoSettings->store_address }}
                                        <br>
                                        <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="fetchStores()">
                                            <i class="fas fa-sync"></i> Refresh Stores
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <h5><i class="icon fas fa-exclamation-triangle"></i> Store Required</h5>
                                        You need to create a store in Pathao before you can create orders. Click "Create Store" below.
                                    </div>
                                @endif

                                @if($pathaoSettings && $pathaoSettings->access_token)
                                    <div class="alert alert-success">
                                        <h5><i class="icon fas fa-check"></i> Connection Status</h5>
                                        <strong>Status:</strong> Connected<br>
                                        @if($pathaoSettings->token_expires_at)
                                            <strong>Token Expires:</strong> {{ $pathaoSettings->token_expires_at->format('Y-m-d H:i:s') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                                @if($pathaoSettings)
                                                                    <button type="button" class="btn btn-success ml-2" onclick="testConnection()">
                                                                        <i class="fas fa-plug"></i> Test Connection
                                                                    </button>
                                                                    <a href="{{ route('admin.pathao.sync-store') }}" class="btn btn-info ml-2">
                                                                        <i class="fas fa-sync"></i> Sync Store
                                                                    </a>                                    @if(!$pathaoSettings->store_name)
                                        <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#createStoreModal">
                                            <i class="fas fa-store"></i> Create Store
                                        </button>
                                    @endif
                                @endif
                                <a href="{{ route('admin.pathao.orders') }}" class="btn btn-info ml-2">
                                    <i class="fas fa-shopping-cart"></i> View Orders
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Store ID Input Modal -->
    <div class="modal fade" id="storeIdModal" tabindex="-1" role="dialog" aria-labelledby="storeIdModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="storeIdModalLabel">Set Store ID</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="manual_store_id">Store ID</label>
                        <input type="number" id="manual_store_id" class="form-control" placeholder="Enter your Pathao Store ID">
                        <small class="form-text text-muted">You can find this in your Pathao merchant dashboard or after store approval.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateStoreId()">Update Store ID</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Store Modal -->
    <div class="modal fade" id="createStoreModal" tabindex="-1" role="dialog" aria-labelledby="createStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createStoreModalLabel">Create Pathao Store</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createStoreForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" name="name" id="store_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_name">Contact Person Name</label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="secondary_contact">Secondary Contact (Optional)</label>
                                    <input type="text" name="secondary_contact" id="secondary_contact" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id">City</label>
                                    <select name="city_id" id="city_id" class="form-control" required onchange="loadZones()">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zone_id">Zone</label>
                                    <select name="zone_id" id="zone_id" class="form-control" required onchange="loadAreas()">
                                        <option value="">Select Zone</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area_id">Area</label>
                                    <select name="area_id" id="area_id" class="form-control" required>
                                        <option value="">Select Area</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="otp_number">OTP Number (Optional)</label>
                                    <input type="text" name="otp_number" id="otp_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Store Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const environmentSelect = document.getElementById('environment');
            const baseUrlInput = document.getElementById('base_url');
            
            environmentSelect.addEventListener('change', function() {
                if (this.value === 'sandbox') {
                    baseUrlInput.value = 'https://courier-api-sandbox.pathao.com';
                } else {
                    baseUrlInput.value = 'https://api-hermes.pathao.com';
                }
            });
        });

        function testConnection() {
            const button = event.target;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
            button.disabled = true;
            
            fetch('{{ route("admin.pathao.test-connection") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Connection successful! ' + data.message);
                    location.reload(); // Reload to show updated connection status
                } else {
                    alert('Connection failed: ' + data.message);
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

        // Load cities when modal opens
        $('#createStoreModal').on('show.bs.modal', function() {
            loadCities();
        });

        function loadCities() {
            fetch('{{ route("admin.pathao.cities") }}')
                .then(response => response.json())
                .then(data => {
                    const citySelect = document.getElementById('city_id');
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = city.city_name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading cities:', error));
        }

        function loadZones() {
            const cityId = document.getElementById('city_id').value;
            if (!cityId) return;

            fetch(`{{ route("admin.pathao.zones") }}?city_id=${cityId}`)
                .then(response => response.json())
                .then(data => {
                    const zoneSelect = document.getElementById('zone_id');
                    zoneSelect.innerHTML = '<option value="">Select Zone</option>';
                    data.forEach(zone => {
                        const option = document.createElement('option');
                        option.value = zone.zone_id;
                        option.textContent = zone.zone_name;
                        zoneSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading zones:', error));
        }

        function loadAreas() {
            const zoneId = document.getElementById('zone_id').value;
            if (!zoneId) return;

            fetch(`{{ route("admin.pathao.areas") }}?zone_id=${zoneId}`)
                .then(response => response.json())
                .then(data => {
                    const areaSelect = document.getElementById('area_id');
                    areaSelect.innerHTML = '<option value="">Select Area</option>';
                    data.forEach(area => {
                        const option = document.createElement('option');
                        option.value = area.area_id;
                        option.textContent = area.area_name;
                        areaSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading areas:', error));
        }

        function showStoreIdInput() {
            $('#storeIdModal').modal('show');
        }

        function updateStoreId() {
            const storeId = document.getElementById('manual_store_id').value;
            if (!storeId) {
                alert('Please enter a Store ID');
                return;
            }

            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            button.disabled = true;

            fetch('{{ route("admin.pathao.update-store-id") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ store_id: storeId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Store ID updated successfully!');
                    $('#storeIdModal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to update Store ID: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update Store ID: ' + error.message);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function fetchStores() {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
            button.disabled = true;

            fetch('{{ route("admin.pathao.fetch-stores") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.stores && data.stores.length > 0) {
                            let message = 'Found ' + data.stores.length + ' stores:\n\n';
                            data.stores.forEach(store => {
                                message += `Store ID: ${store.store_id}\nStore Name: ${store.store_name}\nAddress: ${store.store_address}\n\n`;
                            });
                            alert(message);
                        } else {
                            alert('No stores found. Please create a store first.');
                        }
                    } else {
                        alert('Failed to fetch stores: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to fetch stores: ' + error.message);
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Handle store creation form submission
        document.getElementById('createStoreForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
            button.disabled = true;
            
            fetch('{{ route("admin.pathao.create-store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Store created successfully! ' + data.message);
                    $('#createStoreModal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to create store: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to create store: ' + error.message);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    </script>
@endsection
