@if ($page == 'charge')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="charge">
            <div class="card-body">
                <div class="form-group">
                    <label for="delivery_charge_inside_title" class="col-form-label">
                        Delivery Charge Inside Dhaka - Title
                    </label>
                    <input type="text" name="delivery_charge_inside_title"
                        value="{{ old('delivery_charge_inside_title', $settings?->delivery_charge_inside_title) }}"
                        id="delivery_charge_inside_title"
                        class="form-control @error('delivery_charge_inside_title') is-invalid @enderror"
                        placeholder="Inside Dhaka">
                    @error('delivery_charge_inside_title')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="delivery_charge" class="col-form-label">
                        Delivery Charge Inside Dhaka
                    </label>
                    <input type="number" step="any" name="delivery_charge"
                        value="{{ old('delivery_charge', $settings?->delivery_charge) }}" id="delivery_charge"
                        class="form-control @error('delivery_charge') is-invalid @enderror" placeholder="100">
                    @error('delivery_charge')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <hr>
                <div class="form-group">
                    <label for="delivery_charge_sub_area_title" class="col-form-label">
                        Delivery Charge Inside Dhaka - Sub area - Title
                    </label>
                    <input type="text" name="delivery_charge_sub_area_title"
                        value="{{ old('delivery_charge_sub_area_title', $settings?->delivery_charge_sub_area_title) }}"
                        id="delivery_charge_sub_area_title"
                        class="form-control @error('delivery_charge_sub_area_title') is-invalid @enderror"
                        placeholder="Sub Area">
                    @error('delivery_charge_sub_area_title')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="delivery_charge_sub_area" class="col-form-label">
                        Delivery Charge Inside Dhaka - Sub area
                    </label>
                    <input type="number" step="any" name="delivery_charge_sub_area"
                        value="{{ old('delivery_charge_sub_area', $settings?->delivery_charge_sub_area) }}"
                        id="delivery_charge_sub_area"
                        class="form-control @error('delivery_charge_sub_area') is-invalid @enderror" placeholder="50">
                    @error('delivery_charge_sub_area')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <hr>
                <div class="form-group">
                    <label for="delivery_charge_outside_title" class="col-form-label">
                        Delivery Charge Outside Dhaka - Title
                    </label>
                    <input type="text" name="delivery_charge_outside_title"
                        value="{{ old('delivery_charge_outside_title', $settings?->delivery_charge_outside_title) }}"
                        id="delivery_charge_outside_title"
                        class="form-control @error('delivery_charge_outside_title') is-invalid @enderror"
                        placeholder="Outside Dhaka">
                    @error('delivery_charge_outside_title')
                        <span class="error invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="delivery_charge_outside_dhaka" class="col-form-label">
                        Delivery Charge Outside Dhaka
                    </label>
                    <input type="number" step="any" name="delivery_charge_outside_dhaka"
                        value="{{ old('delivery_charge_outside_dhaka', $settings?->delivery_charge_outside_dhaka) }}"
                        id="delivery_charge_outside_dhaka"
                        class="form-control @error('delivery_charge_outside_dhaka') is-invalid @enderror"
                        placeholder="100">
                    @error('delivery_charge_outside_dhaka')
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
