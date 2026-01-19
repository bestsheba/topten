@if ($page == 'online-payment')
    {{-- <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->get('tab') == 'manual' ? 'active' : '' }}"
                                                    href="{{ route('admin.settings', 'online-payment?tab=manual') }}">
                                                    Manual Payment
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->get('tab') == 'sslcommerz' ? 'active' : '' }}"
                                                    href="{{ route('admin.settings', 'online-payment?tab=sslcommerz') }}">
                                                    SSLCommerz
                                                </a>
                                            </li>
                                        </ul>
                                    </div> --}}
    @if (request()->get('tab') == 'manual')
        <div class="post">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="action" name="online_payment_gateway" value="manual" hidden>
                {{-- Pass which manual gateway block was used so controller only updates that block --}}
                <input type="hidden" name="manual_focus" value="{{ request()->get('focus') }}">
                <div class="card-body">
                    {{-- Per-method enable switches (like SSLCommerz) --}}
                    {{-- bKash switch (shows only in bkash block) --}}

                    {{-- bKash -- show when no focus or focus=bkash --}}
                    @if (!request()->get('focus') || request()->get('focus') == 'bkash')
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="bkash_enabled"
                                        name="bkash_enabled" value="1"
                                        {{ isset($settings) && $settings->bkash_enabled ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="bkash_enabled">
                                        <strong>Status</strong>
                                        <small class="text-muted d-block">Enable/Disable
                                            bKash Option</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">

                            <label for="bkash_number" class="col-form-label">
                                bKash Number
                            </label>
                            <input type="text" name="bkash_number"
                                value="{{ old('bkash_number', $settings?->bkash_number) }}" id="bkash_number"
                                class="form-control @error('bkash_number') is-invalid @enderror"
                                placeholder="bKash Number">
                            @error('bkash_number')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">

                            <label for="bkash_number_note" class="col-form-label">
                                bKash Number Note
                            </label>
                            <input type="text" name="bkash_number_note"
                                value="{{ old('bkash_number_note', $settings?->bkash_number_note) }}"
                                id="bkash_number_note"
                                class="form-control @error('bkash_number_note') is-invalid @enderror"
                                placeholder="bKash Number Note">
                            @error('bkash_number_note')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- Nagad -- show when no focus or focus=nagad --}}
                    @if (!request()->get('focus') || request()->get('focus') == 'nagad')
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="nagad_enabled"
                                        name="nagad_enabled" value="1"
                                        {{ isset($settings) && $settings->nagad_enabled ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="nagad_enabled">
                                        <strong>Status</strong>
                                        <small class="text-muted d-block">Enable/Disable
                                            Nagad Option</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nagad_number" class="col-form-label">
                                Nagad Number
                            </label>
                            <input type="text" name="nagad_number"
                                value="{{ old('nagad_number', $settings?->nagad_number) }}" id="nagad_number"
                                class="form-control @error('nagad_number') is-invalid @enderror"
                                placeholder="Nagad Number">
                            @error('nagad_number')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nagad_number_note" class="col-form-label">
                                Nagad Number Note
                            </label>
                            <input type="text" name="nagad_number_note"
                                value="{{ old('nagad_number_note', $settings?->nagad_number_note) }}"
                                id="nagad_number_note"
                                class="form-control @error('nagad_number_note') is-invalid @enderror"
                                placeholder="Nagad Number Note">
                            @error('nagad_number_note')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- Rocket -- show when no focus or focus=rocket --}}
                    @if (!request()->get('focus') || request()->get('focus') == 'rocket')
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="rocket_enabled"
                                        name="rocket_enabled" value="1"
                                        {{ isset($settings) && $settings->rocket_enabled ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="rocket_enabled">
                                        <strong>Status</strong>
                                        <small class="text-muted d-block">Enable/Disable
                                            Rocket Option</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rocket_number" class="col-form-label">
                                Rocket Number
                            </label>
                            <input type="text" name="rocket_number"
                                value="{{ old('rocket_number', $settings?->rocket_number) }}" id="rocket_number"
                                class="form-control @error('rocket_number') is-invalid @enderror"
                                placeholder="Rocket Number">
                            @error('rocket_number')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="rocket_number_note" class="col-form-label">
                                Rocket Number Note
                            </label>
                            <input type="text" name="rocket_number_note"
                                value="{{ old('rocket_number_note', $settings?->rocket_number_note) }}"
                                id="rocket_number_note"
                                class="form-control @error('rocket_number_note') is-invalid @enderror"
                                placeholder="Rocket Number Note">
                            @error('rocket_number_note')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif

                    {{-- Bank -- show when no focus or focus=bank --}}
                    @if (!request()->get('focus') || request()->get('focus') == 'bank')
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="bank_enabled"
                                        name="bank_enabled" value="1"
                                        {{ isset($settings) && $settings->bank_enabled ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="bank_enabled">
                                        <strong>Status</strong>
                                        <small class="text-muted d-block">Enable/Disable
                                            Bank Option</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bank_account_number" class="col-form-label">
                                Bank Account Number
                            </label>
                            <textarea id="bank_account_number" name="bank_account_number"
                                class="form-control @error('bank_account_number') is-invalid @enderror" placeholder="Bank Account Number">{{ old('bank_account_number', $settings?->bank_account_number) }}</textarea>
                            @error('bank_account_number')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bank_account_number_note" class="col-form-label">
                                Bank Account Number Note
                            </label>
                            <textarea id="bank_account_number_note" name="bank_account_number_note"
                                class="form-control @error('bank_account_number_note') is-invalid @enderror" placeholder="Bank Account Number">{{ old('bank_account_number_note', $settings?->bank_account_number_note) }}</textarea>
                            @error('bank_account_number_note')
                                <span class="error invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    @endif
    @if (request()->get('tab') == 'sslcommerz')
        <div class="post">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                <div class="card-header">
                    <h3 class="card-title">SSLCommerz API Configuration</h3>
                </div>
                @csrf
                @method('PUT')
                <input type="action" name="online_payment_gateway" value="sslcommerz" hidden>
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="sslcommerz_enabled"
                                    name="sslcommerz_enabled" value="1"
                                    {{ $settings->sslcommerz_enabled ? 'checked' : '' }}>
                                <label class="custom-control-label" for="sslcommerz_enabled">
                                    <strong>Status</strong>
                                    <small class="text-muted d-block">
                                        Enable/Disable SSLCommerz Option
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sslcommerz_store_id" class="col-form-label">
                            Store ID
                        </label>
                        <input type="text" name="sslcommerz_store_id"
                            value="{{ $settings->sslcommerz_store_id }}" id="sslcommerz_store_id"
                            class="form-control @error('sslcommerz_store_id') is-invalid @enderror"
                            placeholder="Your Store ID">
                        @error('sslcommerz_store_id')
                            <span class="error invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sslcommerz_store_password" class="col-form-label">
                            Store Password
                        </label>
                        <input type="text" name="sslcommerz_store_password"
                            value="{{ $settings->sslcommerz_store_password }}" id="sslcommerz_store_password"
                            class="form-control @error('sslcommerz_store_password') is-invalid @enderror"
                            placeholder="Your Store Password">
                        @error('sslcommerz_store_password')
                            <span class="error invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sslcommerz_api_endpoint" class="col-form-label">
                            API Endpoint
                        </label>
                        <input type="url" name="sslcommerz_api_endpoint"
                            value="{{ $settings->sslcommerz_api_endpoint }}" id="sslcommerz_api_endpoint"
                            class="form-control @error('sslcommerz_api_endpoint') is-invalid @enderror"
                            placeholder="https://sandbox.sslcommerz.com OR https://securepay.sslcommerz.com">
                        @error('sslcommerz_api_endpoint')
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
    @if (request()->get('tab') == 'stripe')
        <div class="post">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                <div class="card-header">
                    <h3 class="card-title">Stripe API Configuration</h3>
                </div>
                @csrf
                @method('PUT')
                <input type="action" name="online_payment_gateway" value="stripe" hidden>
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="stripe_enabled"
                                    name="stripe_enabled" value="1"
                                    {{ $settings->stripe_enabled ? 'checked' : '' }}>
                                <label class="custom-control-label" for="stripe_enabled">
                                    <strong>Status</strong>
                                    <small class="text-muted d-block">
                                        Enable/Disable Stripe Option
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stripe_mode" class="col-form-label">
                            Mode
                        </label>
                        <select name="stripe_mode" id="stripe_mode"
                            class="form-control @error('stripe_mode') is-invalid @enderror">
                            <option value="test" {{ $settings->stripe_mode == 'test' ? 'selected' : '' }}>
                                Test Mode
                            </option>
                            <option value="live" {{ $settings->stripe_mode == 'live' ? 'selected' : '' }}>
                                Live Mode
                            </option>
                        </select>
                        @error('stripe_mode')
                            <span class="error invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stripe_public_key" class="col-form-label">
                            Public Key
                        </label>
                        <input type="text" name="stripe_public_key" value="{{ $settings->stripe_public_key }}"
                            id="stripe_public_key"
                            class="form-control @error('stripe_public_key') is-invalid @enderror"
                            placeholder="Your Stripe Public Key">
                        @error('stripe_public_key')
                            <span class="error invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stripe_secret_key" class="col-form-label">
                            Secret Key
                        </label>
                        <input type="text" name="stripe_secret_key" value="{{ $settings->stripe_secret_key }}"
                            id="stripe_secret_key"
                            class="form-control @error('stripe_secret_key') is-invalid @enderror"
                            placeholder="Your Stripe Secret Key">
                        @error('stripe_secret_key')
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
@endif
