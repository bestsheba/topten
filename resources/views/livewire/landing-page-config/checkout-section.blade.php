<div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Section Title</label>
            <input type="text" wire:change="updateData('title', $event.target.value)" value="{{ $data['title'] ?? '' }}"
                class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Product Name</label>
            <input type="text" wire:change="updateData('product_name', $event.target.value)"
                value="{{ $data['product_name'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Product Price (Display)</label>
            <input type="text" wire:change="updateData('product_price', $event.target.value)"
                value="{{ $data['product_price'] ?? '' }}" class="form-control form-control-sm"
                placeholder="e.g., 1499.00">
            <small class="form-text text-muted">This is for display only</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Total Price (Order Total) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" wire:change="updateData('total_price', $event.target.value)"
                value="{{ $data['total_price'] ?? $data['product_price'] ?? '' }}" class="form-control form-control-sm"
                placeholder="e.g., 1499.00" required>
            <small class="form-text text-muted">This value will be used as the order total</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Delivery Charge Text</label>
            <input type="text" wire:change="updateData('delivery_charge', $event.target.value)"
                value="{{ $data['delivery_charge'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-12 mb-3">
            <label class="form-label font-weight-bold">Discount Text</label>
            <input type="text" wire:change="updateData('discount_text', $event.target.value)"
                value="{{ $data['discount_text'] ?? '' }}" class="form-control form-control-sm">
        </div>
    </div>
</div>
