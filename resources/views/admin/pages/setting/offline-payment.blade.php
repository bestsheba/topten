@if ($page == 'offline-payment')
    <div class="post">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="offline-payment">
            <div class="card-body">
                <!-- Payment Method Enable/Disable Section -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Offline Payment Method Settings</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="cash_on_delivery_enabled"
                                    name="cash_on_delivery_enabled" value="1"
                                    {{ $settings->cash_on_delivery_enabled ? 'checked' : '' }}>
                                <label class="custom-control-label" for="cash_on_delivery_enabled">
                                    <strong>Cash On Delivery</strong>
                                    <small class="text-muted d-block">Enable/Disable Cash
                                        On Delivery payment method</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endif
