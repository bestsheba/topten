@if ($page == 'site')
    <div class="post">
        <form action="{{ route('admin.site.color') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- Primary Color -->
                <div class="form-group">
                    <label for="primary_color" class="col-form-label">
                        Primary Color
                    </label>
                    <input type="text" name="primary_color" value="{{ $settings->primary_color ?? '#000000' }}"
                        id="primary_color" class="form-control @error('primary_color') is-invalid @enderror"
                        placeholder="#000000">
                    @error('primary_color')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Secondary Color -->
                <div class="form-group">
                    <label for="secondary_color" class="col-form-label">
                        Secondary Color
                    </label>
                    <input type="text" name="secondary_color" value="{{ $settings->secondary_color ?? '#000000' }}"
                        id="secondary_color" class="form-control @error('secondary_color') is-invalid @enderror"
                        placeholder="#000000">
                    @error('secondary_color')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Text Color -->
                <div class="form-group">
                    <label for="text_color" class="col-form-label">
                        Text Color
                    </label>
                    <input type="text" name="text_color" value="{{ $settings->text_color ?? '#000000' }}"
                        id="text_color" class="form-control @error('text_color') is-invalid @enderror"
                        placeholder="#000000">
                    @error('text_color')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endif
