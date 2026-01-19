@if ($page == 'home')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="basic" id="">
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="col-form-label">
                        Website Name
                    </label>
                    <input type="text" id="name" value="{{ old('website_name', $settings?->website_name) }}"
                        name="website_name" class="form-control @error('website_name') is-invalid @enderror"
                        placeholder="Website Name">
                    @error('website_name')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputNumber" class="col-form-label">
                        Old Header Logo
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ $settings?->website_logo_path }}"
                            alt="{{ $settings?->website_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="website_logo" class="col-form-label">
                        Choose Header Logo
                    </label>
                    <input class="form-control @error('website_logo') is-invalid @enderror" type="file"
                        name="website_logo" id="website_logo" accept="image/*" style="height: unset">
                    @error('website_logo')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputNumber" class="col-form-label">
                        Old Footer Logo
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ asset($settings?->footer_logo) }}"
                            alt="{{ $settings?->website_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="footer_logo" class="col-form-label">
                        Choose Footer logo
                    </label>
                    <input class="form-control @error('footer_logo') is-invalid @enderror" type="file"
                        name="footer_logo" id="footer_logo" accept="image/*" style="height: unset">
                    @error('footer_logo')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="old_favicon" class="col-form-label">
                        Old Favicon
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ $settings?->website_favicon_path }}"
                            alt="{{ $settings?->website_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="website_favicon" class="col-form-label">
                        Choose New Favicon
                    </label>
                    <input class="form-control @error('website_favicon') is-invalid @enderror" type="file"
                        name="website_favicon" id="website_favicon" accept="image/*" style="height: unset">
                    @error('website_favicon')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="old_favicon" class="col-form-label">
                        Old Payment Logo
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ asset($settings->payment_logo) }}"
                            alt="{{ $settings?->payment_logo_path }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment_logo" class="col-form-label">
                        Choose New Payment Logo
                    </label>
                    <input class="form-control @error('payment_logo') is-invalid @enderror" type="file"
                        name="payment_logo" id="payment_logo" accept="image/*" style="height: unset">
                    @error('payment_logo')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="hero_section_banner" class="col-form-label">
                        Old Hero Section Banner
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ asset($settings->hero_section_banner) }}"
                            alt="{{ $settings?->hero_section_banner }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment_logo" class="col-form-label">
                        Choose New Hero Section Banner Logo
                    </label>
                    <input class="form-control @error('hero_section_banner') is-invalid @enderror" type="file"
                        name="hero_section_banner" id="hero_section_banner" accept="image/*" style="height: unset">
                    @error('hero_section_banner')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hero_section_banner" class="col-form-label">
                        Old Footer Banner
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ asset($settings->footer_banner) }}" alt="Footer Banner">
                    </div>
                </div>
                <div class="form-group">
                    <label for="footer_banner" class="col-form-label">
                        Choose New Footer Banner
                    </label>
                    <input class="form-control @error('footer_banner') is-invalid @enderror" type="file"
                        name="footer_banner" id="footer_banner" accept="image/*" style="height: unset">
                    @error('footer_banner')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="footer_banner_link" class="col-form-label">
                        Footer Banner Link
                    </label>
                    <input class="form-control @error('footer_banner_link') is-invalid @enderror" type="url"
                        name="footer_banner_link" value="{{ $settings->footer_banner_link }}"
                        placeholder="Footer Banner Link" id="footer_banner_link">
                    @error('footer_banner_link')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="popup_image" class="col-form-label">
                        Popup Image
                    </label>
                    <div>
                        <img style="height: 50px" src="{{ asset($settings->popup_image) }}" alt="Popup Image">
                    </div>
                </div>
                <div class="form-group">
                    <label for="popup_image" class="col-form-label">
                        Choose New Popup Image
                    </label>
                    <input class="form-control @error('popup_image') is-invalid @enderror" type="file"
                        name="popup_image" id="popup_image" accept="image/*" style="height: unset">
                    @error('popup_image')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="popup_url" class="col-form-label">
                        Popup URL
                    </label>
                    <input class="form-control @error('popup_url') is-invalid @enderror" type="url"
                        name="popup_url" value="{{ $settings->popup_url }}" placeholder="Popup URL" id="popup_url">
                    @error('popup_url')
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
