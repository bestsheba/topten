@if ($page == 'google-login')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="google-login" id="">
            <div class="card-body">
                <div class="form-group">
                    <label for="google_login_enabled" class="col-form-label">
                        Google Login Status
                    </label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="google_login_enabled" id="google_enabled"
                            value="1"
                            {{ old('google_login_enabled', $settings?->google_login_enabled) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="google_enabled">
                            Enabled
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="google_login_enabled" id="google_disabled"
                            value="0"
                            {{ old('google_login_enabled', $settings?->google_login_enabled) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="google_disabled">
                            Disabled
                        </label>
                    </div>
                    @error('google_login_enabled')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Enable or disable Google login functionality
                    </small>
                </div>

                <div class="form-group">
                    <label for="google_client_id" class="col-form-label">
                        Google Client ID
                    </label>
                    <input type="text" id="google_client_id"
                        value="{{ old('google_client_id', $settings?->google_client_id) }}" name="google_client_id"
                        class="form-control @error('google_client_id') is-invalid @enderror"
                        placeholder="Enter Google Client ID">
                    @error('google_client_id')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Get this from Google Cloud Console -> APIs & Services -> Credentials
                    </small>
                </div>

                <div class="form-group">
                    <label for="google_client_secret" class="col-form-label">
                        Google Client Secret
                    </label>
                    <input type="password" id="google_client_secret"
                        value="{{ old('google_client_secret', $settings?->google_client_secret) }}"
                        name="google_client_secret"
                        class="form-control @error('google_client_secret') is-invalid @enderror"
                        placeholder="Enter Google Client Secret">
                    @error('google_client_secret')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Get this from Google Cloud Console -> APIs & Services -> Credentials
                    </small>
                </div>

                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Setup Instructions:</h5>
                    <ol>
                        <li>Go to <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>
                        </li>
                        <li>Create a new project or select existing one</li>
                        <li>Enable Google+ API</li>
                        <li>Go to "Credentials" and create OAuth 2.0 Client ID</li>
                        <li>Set authorized redirect URI to:
                            <code>{{ url('/auth/google/callback') }}</code>
                        </li>
                        <li>Copy Client ID and Client Secret to the fields above</li>
                    </ol>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
@endif
