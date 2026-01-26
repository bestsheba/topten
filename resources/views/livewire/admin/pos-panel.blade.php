@php
    use Illuminate\Support\Str;
@endphp

<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @error('cart')
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
        </div>
    @enderror
    @error('customer')
        <div class="alert alert-warning">{{ $message }}</div>
    @enderror

    <div class="pos-main-container">
        <div class="pos-content-row" style="background: white">
            <div class="pos-catalog">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2" style="flex-shrink: 0;">
                        <div class="input-group pos-search flex-grow-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search product, SKU or brand"
                                wire:model.live="search">
                        </div>
                        <button class="btn btn-outline-secondary btn-sm ml-2" wire:click="clearSearch">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    <div class="category-pills-container">
                        <button class="category-pill {{ $categoryFilter === 'all' ? 'active' : '' }}"
                            wire:click="$set('categoryFilter', 'all')">
                            All
                        </button>
                        @foreach ($categories as $category)
                            <button class="category-pill {{ $categoryFilter == $category->id ? 'active' : '' }}"
                                wire:click="$set('categoryFilter', {{ $category->id }})">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>

                    <div class="products-container">
                        <div class="products-row">
                            @forelse ($products as $product)
                                <div class="product-card" wire:click="addProduct({{ $product->id }})">
                                    <div class="position-relative">
                                        <img src="{{ $product->picture_url }}" alt="{{ $product->name }}">
                                        <span
                                            class="badge badge-light position-absolute m-2">{{ $product->stock_quantity }}
                                            in stock</span>
                                    </div>
                                    <div class="product-card__body">
                                        <p class="text-muted small mb-1">
                                            {{ $product->category?->name ?? 'Uncategorized' }}
                                        </p>
                                        <h5 class="mb-1">{{ Str::limit($product->name, 30) }}</h5>
                                        <p class="text-muted small mb-2">SKU: {{ $product->sku }}</p>
                                        <div class="product-pricing">
                                            <div>
                                                <h5 class="mb-0">{{ showAmount($product->final_price) }}</h5>
                                                @if ($product->discount_amount > 0)
                                                    <small class="text-success">
                                                        -{{ showAmount($product->discount_amount) }} off
                                                    </small>
                                                @endif
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm pos-add-product-btn {{ isset($cart[$product->id]) ? 'btn-success' : 'btn-dark' }}"
                                                wire:click.stop="addProduct({{ $product->id }})">
                                                {{ isset($cart[$product->id]) ? 'Added' : 'Add' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open fa-2x mb-2"></i>
                                        <p class="mb-0 small">No products found</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pagination-info">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

            {{-- <div class="pos-cart">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Order Summary</h4>
                        <small class="text-muted">{{ $cartCount }} items in cart</small>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger btn-sm" wire:click="clearCart">
                            <i class="fas fa-trash mr-1"></i>Clear
                        </button>
                    </div>
                </div>
                <div class="card-body" style="overflow-y: scroll">

                    

                    <div class="totals-section">
                        <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">Discount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $globalDiscountType === 'flat' ? 'active' : '' }}"
                                        type="button" wire:click="setDiscountType('flat')"
                                        style="font-size: 11px; padding: 6px 10px;">৳</button>
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $globalDiscountType === 'percent' ? 'active' : '' }}"
                                        type="button" wire:click="setDiscountType('percent')"
                                        style="font-size: 11px; padding: 6px 10px;">%</button>
                                </div>
                                <input type="number" min="0" class="form-control"
                                    style="font-size: 12px; padding: 8px 12px; height: 36px;"
                                    wire:model="globalDiscountValue">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="font-size: 11px; padding: 8px 12px;">
                                        {{ $globalDiscountType === 'percent' ? '%' : currencyCode() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">Shipping</label>
                            <div class="d-flex mb-2" style="gap: 6px;">
                                <button
                                    class="btn btn-sm btn-outline-secondary {{ $shippingPreset === 'inside' ? 'active' : '' }}"
                                    style="font-size: 11px; padding: 6px 10px; flex: 1;"
                                    wire:click="useShippingPreset('inside')">
                                    Inside Dhaka
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-secondary {{ $shippingPreset === 'outside' ? 'active' : '' }}"
                                    style="font-size: 11px; padding: 6px 10px; flex: 1;"
                                    wire:click="useShippingPreset('outside')">
                                    Outside Dhaka
                                </button>
                            </div>
                            <input type="number" min="0" class="form-control"
                                style="font-size: 12px; padding: 8px 12px; height: 36px;" wire:model.live="shippingCharge">
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">Tax (%)</label>
                            <input type="number" min="0" class="form-control"
                                style="font-size: 12px; padding: 8px 12px; height: 36px;" wire:model="taxRate">
                        </div>

                        <div class="mb-0">
                            <div class="totals-row">
                                <span>Subtotal</span>
                                <span style="font-weight: 600;">{{ showAmount($cartSubtotal) }}</span>
                            </div>
                            <div class="totals-row">
                                <span>Discount</span>
                                <span class="text-success">- {{ showAmount($discountAmount) }}</span>
                            </div>
                            <div class="totals-row">
                                <span>Tax</span>
                                <span style="font-weight: 600;">{{ showAmount($taxAmount) }}</span>
                            </div>
                            <div class="totals-row">
                                <span>Shipping</span>
                                <span style="font-weight: 600;">{{ showAmount($shippingCharge) }}</span>
                            </div>
                            <div class="totals-row"
                                style="font-size: 16px; font-weight: 700; color: #667eea; margin-top: 10px; padding-top: 10px; border-top: 2px solid #e8ecf4;">
                                <span>Total Payable</span>
                                <span>{{ showAmount($grandTotal) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="pos-customer-card">
                <div class="card-header">
                    <h5 class="mb-0">Cart Details</h5>
                </div>
                <div class="card-body">
                    <div class="customer-form-section">
                        @if ($cartItems->isEmpty())
                            <div class="text-center py-4" style="color: #9ca3af;">
                                <i class="fas fa-shopping-basket fa-3x mb-3" style="opacity: 0.4;"></i>
                                <p class="mb-0 small" style="font-size: 13px;">Add items from the catalog to get started
                                </p>
                            </div>
                        @else
                            <div class="cart-items-container">
                                @foreach ($cartItems as $item)
                                    <div class="cart-item">
                                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}"
                                            style="border: 1px solid #e8ecf4;">
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div style="flex: 1; min-width: 0;">
                                                    <h6 class="mb-1"
                                                        style="font-size: 13px; font-weight: 600; color: #1f2937;">
                                                        {{ $item['name'] }}</h6>
                                                    <small
                                                        style="color: #9ca3af; display: block; margin-bottom: 4px;">{{ $item['sku'] }}</small>
                                                </div>
                                                <div class="text-right" style="flex-shrink: 0; margin-left: 8px;">
                                                    <strong
                                                        style="color: #667eea; display: block; font-size: 13px;">{{ showAmount($item['price'] * $item['quantity']) }}</strong>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-2"
                                                style="gap: 8px;">
                                                <div class="btn-group btn-group-sm cart-qty" role="group">
                                                    <button type="button" class="btn btn-light"
                                                        style="padding: 4px 8px; font-size: 11px;"
                                                        wire:click="decrementQuantity('{{ $item['product_id'] . (isset($item['variation_id']) ? '-' . $item['variation_id'] : '') }}')">−</button>
                                                    <input type="number" min="1"
                                                        class="form-control form-control-sm text-center border-0"
                                                        value="{{ $item['quantity'] }}"
                                                        wire:change="setQuantity('{{ $item['product_id'] . (isset($item['variation_id']) ? '-' . $item['variation_id'] : '') }}', $event.target.value)"
                                                        style="width: 35px; padding: 4px 2px; font-size: 11px; font-weight: 600;">
                                                    <button type="button" class="btn btn-light"
                                                        style="padding: 4px 8px; font-size: 11px;"
                                                        wire:click="incrementQuantity('{{ $item['product_id'] . (isset($item['variation_id']) ? '-' . $item['variation_id'] : '') }}')">+</button>
                                                </div>
                                                <small style="color: #9ca3af; font-size: 10px;">{{ $item['stock'] }}
                                                    left</small>
                                                <button class="btn btn-link p-0"
                                                    style="color: #ef4444; font-size: 11px; text-decoration: none;"
                                                    wire:click="removeItem('{{ $item['product_id'] . (isset($item['variation_id']) ? '-' . $item['variation_id'] : '') }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group position-relative">
                            <label>Customer Name</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background: white; border-right: none;">
                                        <i class="fas fa-user" style="color: #667eea;"></i>
                                    </span>
                                </div>
                                @if ($selectedCustomer)
                                    <input type="text" class="form-control"
                                        value="{{ $selectedCustomer['name'] }}" disabled
                                        style="border-left: none; font-size: 13px; background-color: #f0f9ff;"
                                        placeholder="{{ $selectedCustomer['name'] }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="clearCustomer()" style="background: white;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <input type="text" class="form-control" placeholder="Enter name or phone..."
                                        wire:model.live="customerSearch" style="border-left: none; font-size: 13px;">
                                    @if ($customerSearch)
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                wire:click="$set('customerSearch', '')" style="background: white;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            @if (!empty($customerResults))
                                <div
                                    style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 1000; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.12); max-height: 300px; overflow-y: auto; margin-top: 4px;">
                                    @foreach ($customerResults as $index => $customer)
                                        <button type="button" class="w-100 text-left border-0 p-0"
                                            wire:click="selectCustomer({{ $customer['id'] }})"
                                            style="background: transparent; transition: all 0.2s ease; padding: 0; {{ $index === 0 ? 'border-radius: 8px 8px 0 0;' : '' }} {{ $index === count($customerResults) - 1 ? 'border-radius: 0 0 8px 8px;' : '' }}"
                                            onmouseover="this.style.backgroundColor='#f8f9fa'"
                                            onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="p-3"
                                                style="border-bottom: {{ $index === count($customerResults) - 1 ? 'none' : '1px solid #e8ecf4' }};">
                                                <div
                                                    style="font-weight: 600; color: #1f2937; font-size: 13px; margin-bottom: 4px;">
                                                    {{ $customer['name'] }}
                                                </div>
                                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                    <small
                                                        style="color: #667eea; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class="fas fa-phone" style="font-size: 10px;"></i>
                                                        {{ $customer['phone'] }}
                                                    </small>
                                                    @if ($customer['email'])
                                                        <small
                                                            style="color: #9ca3af; display: inline-flex; align-items: center; gap: 4px;">
                                                            <i class="fas fa-envelope" style="font-size: 10px;"></i>
                                                            {{ $customer['email'] }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif ($customerSearch && empty($customerResults))
                                <div class="alert alert-info py-3 mb-2"
                                    style="font-size: 12px; border-radius: 8px; background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; margin-top: 4px;">
                                    <i class="fas fa-search mr-2"></i>No customers found
                                </div>
                            @endif
                            @if ($selectedCustomer)
                                <small class="text-success"
                                    style="font-size: 11px; display: flex; align-items: center; gap: 6px; margin-top: 6px;">
                                    <i class="fas fa-check-circle"></i> Customer selected
                                </small>
                            @endif
                        </div>
                        {{-- <div class="form-group">
                            <label>Phone number</label>
                            <input type="text" class="form-control" wire:model="customerForm.phone">
                        </div>
                        <div class="form-group">
                            <label>Delivery address</label>
                            <textarea rows="2" class="form-control" wire:model="customerForm.address"></textarea>
                        </div> --}}

                        <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">Discount (Optional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $globalDiscountType === 'flat' ? 'active' : '' }}"
                                        type="button" wire:click="setDiscountType('flat')"
                                        style="font-size: 11px; padding: 6px 10px;">৳</button>
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $globalDiscountType === 'percent' ? 'active' : '' }}"
                                        type="button" wire:click="setDiscountType('percent')"
                                        style="font-size: 11px; padding: 6px 10px;">%</button>
                                </div>
                                <input type="number" min="0" class="form-control"
                                    style="font-size: 12px; padding: 8px 12px; height: 36px;"
                                    wire:model.live="globalDiscountValue" placeholder="Enter discount">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="font-size: 11px; padding: 8px 12px;">
                                        {{ $globalDiscountType === 'percent' ? '%' : currencyCode() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">Shipping</label>
                            <div class="d-flex mb-2" style="gap: 6px;">
                                <button
                                    class="btn btn-sm btn-outline-secondary {{ $shippingPreset === 'inside' ? 'active' : '' }}"
                                    style="font-size: 11px; padding: 6px 10px; flex: 1;"
                                    wire:click="useShippingPreset('inside')">
                                    Inside Dhaka
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-secondary {{ $shippingPreset === 'outside' ? 'active' : '' }}"
                                    style="font-size: 11px; padding: 6px 10px; flex: 1;"
                                    wire:click="useShippingPreset('outside')">
                                    Outside Dhaka
                                </button>
                            </div>
                            <input type="number" min="0" class="form-control"
                                style="font-size: 12px; padding: 8px 12px; height: 36px;"
                                wire:model.live="shippingCharge">
                        </div> --}}
{{-- 
                        <div class="mb-3">
                            <label class="text-uppercase text-muted small"
                                style="font-size: 10px; color: #6b7280;">VAT/Tax (Optional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $taxType === 'flat' ? 'active' : '' }}"
                                        type="button" wire:click="setTaxType('flat')"
                                        style="font-size: 11px; padding: 6px 10px;">৳</button>
                                    <button
                                        class="btn btn-outline-secondary btn-sm {{ $taxType === 'percent' ? 'active' : '' }}"
                                        type="button" wire:click="setTaxType('percent')"
                                        style="font-size: 11px; padding: 6px 10px;">%</button>
                                </div>
                                <input type="number" min="0" class="form-control"
                                    style="font-size: 12px; padding: 8px 12px; height: 36px;"
                                    wire:model.live="taxRate" placeholder="Enter tax">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="font-size: 11px; padding: 8px 12px;">
                                        {{ $taxType === 'percent' ? '%' : currencyCode() }}
                                    </span>
                                </div>
                            </div>
                        </div> --}}
{{-- 
                        <div class="form-group">
                            <label>Payment method</label>
                            <div class="d-flex flex-wrap" style="gap: 4px;">
                                @foreach (['cash' => 'Cash', 'cash_on_delivery' => 'Cash on Delivery', 'online' => 'Online Payment'] as $method => $label)
                                    <button type="button"
                                        class="pos-chip {{ $paymentMethod === $method ? 'bg-dark text-white border-dark' : '' }}"
                                        wire:click="$set('paymentMethod', '{{ $method }}')">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label>Payment status</label>
                            <select class="form-control" wire:model="paymentStatus">
                                <option value="paid">Paid</option>
                                {{-- <option value="pending">Pending</option> --}}
                                <option value="due">Due</option>
                            </select>
                        </div>
                    </div>

                    {{-- Cart Summary --}}
                    @if (!$cartItems->isEmpty())
                        <div class=""
                            style="background: #f9fafb; border: 1px solid #e8ecf4; border-radius: 6px; padding: 8px 10px;">
                            <div class="d-flex justify-content-between align-items-center mb-1"
                                style="color: #6b7280;">
                                <span>Subtotal</span>
                                <span style="font-weight: 600; color: #1f2937;">
                                    {{ showAmount($cartSubtotal) }}
                                </span>
                            </div>

                            @if ($discountAmount > 0)
                                <div class="d-flex justify-content-between align-items-center mb-1"
                                    style="color: #6b7280;">
                                    <span>
                                        Discount
                                        @if ($globalDiscountType === 'percent')
                                            <small>({{ $globalDiscountValue }}%)</small>
                                        @endif
                                    </span>
                                    <span style="font-weight: 600; color: #10b981;">
                                        - {{ showAmount($discountAmount) }}
                                    </span>
                                </div>
                            @endif

                            {{-- <div class="d-flex justify-content-between align-items-center mb-1"
                                style="font-size: 10px; color: #6b7280;">
                                <span>Shipping</span>
                                <span style="font-weight: 600; color: #1f2937;">
                                    {{ showAmount($shippingCharge) }}
                                </span>
                            </div> --}}
{{-- 
                            @if ($taxAmount > 0)
                                <div class="d-flex justify-content-between align-items-center mb-1"
                                    style="font-size: 10px; color: #6b7280;">
                                    <span>
                                        VAT/Tax
                                        @if ($taxType === 'percent' && $taxRate > 0)
                                            <small>({{ $taxRate }}%)</small>
                                        @endif
                                    </span>
                                    <span style="font-weight: 600; color: #1f2937;">
                                        {{ showAmount($taxAmount) }}
                                    </span>
                                </div>
                            @endif --}}

                            <div class="d-flex justify-content-between align-items-center pt-1 mt-1"
                                style="border-top: 1px solid #e8ecf4; font-weight: 700;">
                                <span style="color: #1f2937;">Total</span>
                                <span style="color: #667eea;">{{ showAmount($grandTotal) }}</span>
                            </div>
                        </div>
                    @endif

                    <button class="btn btn-lg btn-success btn-block mt-0" wire:click="placeOrder"
                        wire:loading.attr="disabled" wire:target="placeOrder"
                        {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="placeOrder">
                            <i class="fas fa-check-circle mr-2"></i>Complete Order • {{ showAmount($grandTotal) }}
                        </span>
                        <span wire:loading wire:target="placeOrder">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variation Modal -->
    @if ($showVariationModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1"
            role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Variation</h5>
                        <button type="button" class="close" wire:click="closeVariationModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($selectedProductForVariation)
                            <div class="d-flex mb-3">
                                <img src="{{ $selectedProductForVariation->picture_url }}" class="img-thumbnail mr-3"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ $selectedProductForVariation->name }}</h6>
                                    <p class="text-muted small mb-0">SKU: {{ $selectedProductForVariation->sku }}
                                    </p>
                                </div>
                            </div>

                            @foreach ($variationAttributes as $attributeName => $values)
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ $attributeName }}</label>
                                    <div class="d-flex flex-wrap" style="gap: 8px;">
                                        @foreach ($values as $value)
                                            <button type="button"
                                                class="btn btn-sm {{ $selectedVariation && $selectedVariation->attributeValues->contains('id', $value['id']) ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                wire:click="selectVariation({{ $value['variation_id'] }})">
                                                {{ $value['value'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @if ($selectedVariation)
                                <div class="alert alert-success mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Price:</strong> {{ showAmount($variationPrice) }}
                                            <br>
                                            <small>Stock: {{ $variationStock }}</small>
                                        </div>
                                    </div>
                                </div>
                            @elseif(count($selectedAttributes) === count($variationAttributes))
                                {{-- <div class="alert alert-warning mt-3">
                                    Variation not available or out of stock.
                                </div> --}}
                            @endif

                            @error('variation')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeVariationModal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="addVariationToCart"
                            {{ !$selectedVariation || $variationStock <= 0 ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
