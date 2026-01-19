<div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Background Image URL</label>
            <input type="text" wire:change="updateData('background_image', $event.target.value)"
                value="{{ $data['background_image'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">CTA Button Text</label>
            <input type="text" wire:change="updateData('cta_text', $event.target.value)"
                value="{{ $data['cta_text'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">CTA Button Link</label>
            <input type="text" wire:change="updateData('cta_link', $event.target.value)"
                value="{{ $data['cta_link'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Badge Text</label>
            <input type="text" wire:change="updateData('badge_text', $event.target.value)"
                value="{{ $data['badge_text'] ?? '' }}" class="form-control form-control-sm">
        </div>
    </div>

    @if ($data['background_image'] ?? null)
        <div class="mt-3">
            <small class="form-text text-muted d-block mb-2">Image Preview:</small>
            <img src="{{ $data['background_image'] }}" alt="Hero Background" class="img-fluid"
                style="max-height: 200px;">
        </div>
    @endif
</div>
