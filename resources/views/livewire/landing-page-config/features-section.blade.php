<div>
    <div class="mb-3">
        <label class="form-label font-weight-bold">Section Title</label>
        <input type="text" wire:change="updateData('title', $event.target.value)"
            value="{{ $data['title'] ?? '' }}"
            class="form-control form-control-sm">
    </div>

    <div class="mt-3">
        <label class="form-label font-weight-bold">Features</label>
        @foreach($data['features'] ?? [] as $index => $feature)
            <div class="card card-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Feature {{ $index + 1 }}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Icon/Emoji</label>
                            <input type="text" wire:change="updateFeature({{ $index }}, 'icon', $event.target.value)"
                                value="{{ $feature['icon'] ?? '' }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Title</label>
                            <input type="text" wire:change="updateFeature({{ $index }}, 'title', $event.target.value)"
                                value="{{ $feature['title'] ?? '' }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label small">Description</label>
                            <textarea wire:change="updateFeature({{ $index }}, 'description', $event.target.value)"
                                rows="2" class="form-control form-control-sm">{{ $feature['description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label small">Color Class</label>
                            <input type="text" wire:change="updateFeature({{ $index }}, 'color', $event.target.value)"
                                value="{{ $feature['color'] ?? '' }}"
                                placeholder="e.g., from-red-600 to-red-700"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
