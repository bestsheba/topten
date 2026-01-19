@if ($page == 'affiliate')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="affiliate">
            <div class="card-body">
                <div class="form-group">
                    <label for="affiliate_commission_percent" class="col-form-label">
                        Affiliate Commission (%)
                    </label>
                    <input type="number" step="0.01" min="0" max="100"
                        name="affiliate_commission_percent"
                        value="{{ old('affiliate_commission_percent', $settings?->affiliate_commission_percent) }}"
                        id="affiliate_commission_percent"
                        class="form-control @error('affiliate_commission_percent') is-invalid @enderror"
                        placeholder="e.g. 5">
                    @error('affiliate_commission_percent')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Percentage of commission affiliates will earn from each sale
                        (0-100%)
                    </small>
                </div>
                <div class="form-group">
                    <label for="affiliate_min_withdrawal_amount" class="col-form-label">
                        Minimum Withdrawal Amount
                    </label>
                    <input type="number" step="0.01" min="0" name="affiliate_min_withdrawal_amount"
                        value="{{ old('affiliate_min_withdrawal_amount', $settings?->affiliate_min_withdrawal_amount) }}"
                        id="affiliate_min_withdrawal_amount"
                        class="form-control @error('affiliate_min_withdrawal_amount') is-invalid @enderror"
                        placeholder="e.g. 100">
                    @error('affiliate_min_withdrawal_amount')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Minimum amount affiliates must have in their wallet before they can
                        request a withdrawal
                    </small>
                </div>
                <div class="form-group">
                    <label for="affiliate_feature_enabled" class="col-form-label">
                        Affiliate Feature Status
                    </label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="affiliate_feature_enabled"
                            id="affiliate_enabled" value="1"
                            {{ old('affiliate_feature_enabled', $settings?->affiliate_feature_enabled) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="affiliate_enabled">
                            Enabled
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="affiliate_feature_enabled"
                            id="affiliate_disabled" value="0"
                            {{ old('affiliate_feature_enabled', $settings?->affiliate_feature_enabled) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="affiliate_disabled">
                            Disabled
                        </label>
                    </div>
                    @error('affiliate_feature_enabled')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <small class="form-text text-muted">
                        Enable or disable the affiliate program entirely
                    </small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endif
