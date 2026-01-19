@if ($page == 'flash')
    <div class="post">
        <form action="{{ route('admin.flash.deal.time') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="flash_deal" class="col-form-label">
                        Select Flash Deal Time
                    </label>
                    <input type="date" name="flash_deal"
                        value="{{ $settings->flash_deal_timer ? date('Y-m-d', strtotime($settings->flash_deal_timer)) : date('Y-m-d', strtotime(now())) }}"
                        id="flash_deal" class="form-control @error('flash_deal') is-invalid @enderror"
                        placeholder="bKash Number">
                    @error('flash_deal')
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
