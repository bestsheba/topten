@if ($page == 'footer')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="footer">
            <div class="card-body">
                <div class="form-group">
                    <label for="address" class="col-sm-2 col-form-label">
                        Address
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address"
                        style="height: 100px" placeholder="Address">{{ old('address', $settings?->address) }}</textarea>
                    @error('address')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone_number" class="col-form-label">
                        Phone Number
                    </label>
                    <input type="text" name="phone_number"
                        value="{{ old('phone_number', $settings?->phone_number) }}" id="phone_number"
                        class="form-control @error('phone_number') is-invalid @enderror" placeholder="Phone Number">
                    @error('phone_number')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $settings?->email) }}" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">
                    @error('email')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">
                        Whatsapp URL
                    </label>
                    <input type="text" name="whatsapp_number"
                        value="{{ old('whatsapp_number', $settings?->whatsapp_number) }}" id="whatsapp_number"
                        class="form-control @error('whatsapp_number') is-invalid @enderror"
                        placeholder="Whatsapp Number">
                    @error('whatsapp_number')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="facebook" class="col-form-label">
                        Facebook
                    </label>
                    <input type="url" name="facebook" value="{{ old('facebook', $settings?->facebook) }}"
                        id="facebook" class="form-control @error('facebook') is-invalid @enderror"
                        placeholder="https://bestsheba.com/bestsheba.com">
                    @error('facebook')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="text" class="col-form-label">
                        Youtube
                    </label>
                    <input type="url" name="youtube" value="{{ old('youtube', $settings?->youtube) }}"
                        id="youtube" class="form-control @error('youtube') is-invalid @enderror"
                        placeholder="https://youtube.com/bestsheba.com">
                    @error('youtube')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="instagram" class="col-form-label">
                        Instagram
                    </label>
                    <input type="url" name="instagram" value="{{ old('instagram', $settings?->instagram) }}"
                        id="instagram" class="form-control @error('instagram') is-invalid @enderror"
                        placeholder="https://instagram.com/bestsheba.com">
                    @error('instagram')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tiktok" class="col-form-label">
                        TikTok
                    </label>
                    <input type="url" name="tiktok" value="{{ old('tiktok', $settings?->tiktok) }}" id="tiktok"
                        class="form-control @error('tiktok') is-invalid @enderror"
                        placeholder="https://tiktok.com/@bestsheba.com">
                    @error('tiktok')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="copyright" class="col-form-label">
                        Copyright Text
                    </label>
                    <input type="url" name="copyright" value="{{ old('copyright', $settings?->copyright) }}"
                        id="copyright" class="form-control @error('copyright') is-invalid @enderror"
                        placeholder="Copyright Text">
                    @error('copyright')
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
