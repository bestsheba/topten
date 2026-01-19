<div>
    <div class="row">
        <div class="col-12 mb-3">
            <label class="form-label font-weight-bold">Section Title</label>
            <input type="text" wire:change="updateData('title', $event.target.value)" value="{{ $data['title'] ?? '' }}"
                class="form-control form-control-sm">
        </div>

        <div class="col-12 mb-3">
            <label class="form-label font-weight-bold">Section Subtitle</label>
            <input type="text" wire:change="updateData('subtitle', $event.target.value)"
                value="{{ $data['subtitle'] ?? '' }}" class="form-control form-control-sm">
        </div>

        <div class="col-12">
            <label class="form-label font-weight-bold">Packages</label>
            <div class="mt-3">
                @foreach ($data['packages'] ?? [] as $index => $package)
                    <div class="card card-sm mb-3 border-danger">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <strong>Package {{ $index + 1 }}</strong>
                            <button type="button" wire:click="removePackage({{ $index }})"
                                class="btn btn-sm btn-danger">
                                ✕ Remove
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label small">📦 Product থেকে নির্বাচন করুন</label>
                                    <select wire:change="updatePackageProduct({{ $index }}, $event.target.value)"
                                        class="form-control form-control-sm">
                                        <option value="">-- পণ্য বেছে নিন --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                @if (($package['product_id'] ?? null) == $product->id) selected @endif>
                                                {{ $product->name }} (৳{{ $product->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Name</label>
                                    <input type="text"
                                        wire:change="updatePackage({{ $index }}, 'name', $event.target.value)"
                                        value="{{ $package['name'] ?? '' }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Image URL</label>
                                    <input type="text"
                                        wire:change="updatePackage({{ $index }}, 'image', $event.target.value)"
                                        value="{{ $package['image'] ?? '' }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Badge</label>
                                    <input type="text"
                                        wire:change="updatePackage({{ $index }}, 'badge', $event.target.value)"
                                        value="{{ $package['badge'] ?? '' }}" class="form-control form-control-sm">
                                </div>

                                @if ($package['image'] ?? null)
                                    <div class="col-12 mt-2">
                                        <small class="form-text text-muted d-block mb-2">Image Preview:</small>
                                        <div
                                            style="width: 100%; height: 200px; overflow: hidden; border-radius: 4px; background: #f0f0f0;">
                                            <img src="{{ $package['image'] }}" alt="Package" class="img-fluid"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-3">
                    <button type="button" wire:click="addPackage" class="btn btn-sm btn-success">
                        ➕ Add New Package
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
