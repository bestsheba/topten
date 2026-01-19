<div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Section Title</label>
            <input type="text" wire:change="updateData('title', $event.target.value)" value="{{ $data['title'] ?? '' }}"
                class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Subtitle</label>
            <input type="text" wire:change="updateData('subtitle', $event.target.value)"
                value="{{ $data['subtitle'] ?? '' }}" class="form-control form-control-sm">
        </div>
    </div>

    <div class="mt-4 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Testimonials</h6>
            <button type="button" wire:click="addTestimonial" class="btn btn-sm btn-success">
                ➕ Add Testimonial
            </button>
        </div>
    </div>

    <div class="row">
        @foreach ($data['testimonials'] ?? [] as $index => $testimonial)
            <div class="col-12 mb-4 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Testimonial #{{ $index + 1 }}</strong>
                    <button type="button" wire:click="removeTestimonial({{ $index }})" class="btn btn-sm btn-danger">
                        🗑️ Remove
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label font-weight-bold">Image</label>
                        <input type="file" 
                            wire:model="tempImages.{{ $index }}"
                            accept="image/*"
                            class="form-control form-control-sm">
                        @error("tempImages.{$index}")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if(isset($testimonial['image']) && $testimonial['image'])
                            <div class="mt-2">
                                <img src="{{ asset($testimonial['image']) }}" alt="Preview" 
                                    class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        @if(isset($tempImages[$index]))
                            <div class="mt-2">
                                <small class="text-info">Uploading...</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Rating</label>
                        <input type="text" 
                            wire:change="updateTestimonial({{ $index }}, 'rating', $event.target.value)"
                            value="{{ $testimonial['rating'] ?? '⭐⭐⭐⭐⭐' }}"
                            class="form-control form-control-sm"
                            placeholder="⭐⭐⭐⭐⭐">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Review Text</label>
                        <textarea 
                            wire:change="updateTestimonial({{ $index }}, 'text', $event.target.value)"
                            class="form-control form-control-sm"
                            rows="2"
                            placeholder="Review text...">{{ $testimonial['text'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(empty($data['testimonials']))
        <div class="alert alert-info" role="alert">
            <small><strong>💡 Note:</strong> No testimonials yet. Click "Add Testimonial" to add one.</small>
        </div>
    @endif
</div>
