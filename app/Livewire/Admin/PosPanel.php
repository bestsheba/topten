<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductVariation;

use function Pest\Laravel\session;

class PosPanel extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = 'all';
    public int $perPage = 10;
    public array $cart = [];
    public string $customerSearch = '';
    public bool $customerSearchEnabled = false;
    public array $customerResults = [];
    public array $customerForm = [
        'name' => '',
        'phone' => '',
        'address' => '',
    ];
    public ?array $selectedCustomer = null;
    public string $globalDiscountType = 'flat';
    public float $globalDiscountValue = 0.0;
    public float $shippingCharge = 0.0;
    public string $shippingPreset = '';
    public string $taxType = 'percent';
    public float $taxRate = 0.0;
    public string $paymentMethod = 'cash';
    public string $paymentStatus = 'paid';
    public string $transactionCode = '';
    public float $insideDhakaCharge = 0.0;
    public float $outsideDhakaCharge = 0.0;

    // Variation Modal Properties
    public bool $showVariationModal = false;
    public ?Product $selectedProductForVariation = null;
    public $productVariations = [];
    public $variationAttributes = [];
    public $selectedAttributes = [];
    public ?ProductVariation $selectedVariation = null;
    public $variationPrice = 0;
    public $variationStock = 0;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        abort_if(!userCan('view orders'), 403);

        $settings = Setting::first();
        $this->insideDhakaCharge = (float)($settings->delivery_charge ?? 0);
        $this->outsideDhakaCharge = (float)($settings->delivery_charge_outside_dhaka ?? $this->insideDhakaCharge);
        $this->shippingCharge = $this->insideDhakaCharge;
        $this->customerForm = [
            'name' => 'Walk-in Customer',
            'phone' => '',
            'address' => '',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedCustomerSearchEnabled(): void
    {
        if (!$this->customerSearchEnabled) {
            $this->customerSearch = '';
            $this->customerResults = [];
        }
    }

    public function updatedCustomerSearch(): void
    {
        $term = trim($this->customerSearch);

        if (strlen($term) < 2) {
            $this->customerResults = [];
            return;
        }

        // Search from User table with UserAddress relation
        $this->customerResults = User::query()
            ->with('address')
            ->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhereHas('address', function ($query) use ($term) {
                $query->where('phone_number', 'like', "%{$term}%");
            })
            ->limit(6)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->address?->phone_number ?? '',
                    'email' => $user->email,
                    'address' => $user->address?->address ?? '',
                ];
            })
            ->toArray();
    }

    public function selectCustomer(int $customerId): void
    {
        $user = User::with('address')->find($customerId);

        if (!$user) {
            return;
        }

        $this->selectedCustomer = [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->address?->phone_number ?? '',
            'address' => $user->address?->address ?? '',
        ];

        $this->customerForm = [
            'name' => $this->selectedCustomer['name'],
            'phone' => $this->selectedCustomer['phone'] ?? '',
            'address' => $this->selectedCustomer['address'] ?? '',
        ];

        $this->customerSearch = '';
        $this->customerResults = [];
    }

    public function clearCustomer(): void
    {
        $this->selectedCustomer = null;
        $this->customerForm = [
            'name' => 'Walk-in Customer',
            'phone' => '',
            'address' => '',
        ];
        $this->customerSearch = '';
    }

    public function addProduct(int $productId): void
    {
        $product = Product::active()->with(['variations.attributeValues.attribute'])->find($productId);

        if (!$product) {
            return;
        }

        // Check if product has variations
        if ($product->variations->isNotEmpty()) {
            $this->openVariationModal($product);
            return;
        }

        if ($product->stock_quantity <= 0) {
            $this->addError('cart', "{$product->name} is out of stock.");
            return;
        }

        $cartKey = $product->id; // Simple product key

        $existingQty = $this->cart[$cartKey]['quantity'] ?? 0;
        $newQty = min($existingQty + 1, max($product->stock_quantity, 1));

        $this->cart[$cartKey] = [
            'product_id' => $product->id,
            'variation_id' => null,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => (float) $product->final_price,
            'quantity' => $newQty,
            'stock' => $product->stock_quantity,
            'thumbnail' => $product->picture_url,
            'category' => $product->category?->name,
            'options' => [],
        ];
    }

    public function openVariationModal(Product $product)
    {
        $this->selectedProductForVariation = $product;
        $this->productVariations = $product->variations;
        $this->prepareAttributes($product);
        $this->showVariationModal = true;
        $this->selectedAttributes = [];
        $this->selectedVariation = null;
        $this->variationPrice = 0;
        $this->variationStock = 0;
    }

    public function prepareAttributes($product)
    {
        // Sort variations by price (lowest first)
        $sortedVariations = $product->variations->sortBy('price');

        // Collect unique attributes for the product
        $this->variationAttributes = $sortedVariations
            ->flatMap(function ($variation) {
                return $variation->attributeValues->map(function ($attributeValue) use ($variation) {
                    return [
                        'attribute' => $attributeValue->attribute->name,
                        'value' => $attributeValue->value,
                        'id' => $attributeValue->id,
                        'variation_id' => $variation->id, // Use the variation ID directly
                        'price' => $variation->price
                    ];
                });
            })
            ->groupBy('attribute')
            ->map(function ($values) {
                return $values->sortBy('price')
                    ->unique('value')
                    ->values()
                    ->map(function ($item) {
                        unset($item['price']);
                        return $item;
                    })
                    ->toArray();
            })
            ->toArray();
    }

    public function selectVariation($variationId)
    {
        // Toggle logic: if already selected, unselect
        if ($this->selectedVariation && $this->selectedVariation->id === $variationId) {
            $this->selectedVariation = null;
            $this->variationPrice = 0;
            $this->variationStock = 0;
            return;
        }

        $this->selectedVariation = $this->productVariations->firstWhere('id', $variationId);

        if ($this->selectedVariation) {
            $this->variationPrice = $this->selectedVariation->price;
            $this->variationStock = $this->selectedVariation->stock;
        }
    }

    public function addVariationToCart()
    {
        if (!$this->selectedVariation) {
            return;
        }

        if ($this->selectedVariation->stock <= 0) {
            $this->addError('variation', "Selected variation is out of stock.");
            return;
        }

        $product = $this->selectedProductForVariation;
        $cartKey = $product->id . '-' . $this->selectedVariation->id;

        $existingQty = $this->cart[$cartKey]['quantity'] ?? 0;
        $newQty = min($existingQty + 1, $this->selectedVariation->stock);

        // Format options string
        $options = $this->selectedVariation->attributeValues->map(function ($av) {
            return $av->attribute->name . ': ' . $av->value;
        })->implode(', ');

        $this->cart[$cartKey] = [
            'product_id' => $product->id,
            'variation_id' => $this->selectedVariation->id,
            'name' => $product->name . ' (' . $options . ')',
            'sku' => $this->selectedVariation->sku,
            'price' => (float) $this->selectedVariation->price,
            'quantity' => $newQty,
            'stock' => $this->selectedVariation->stock,
            'thumbnail' => $this->selectedVariation->image ? asset('storage/' . $this->selectedVariation->image) : $product->picture_url,
            'category' => $product->category?->name,
            'options' => $options,
        ];

        $this->closeVariationModal();
    }

    public function closeVariationModal()
    {
        $this->showVariationModal = false;
        $this->selectedProductForVariation = null;
        $this->productVariations = [];
        $this->variationAttributes = [];
        $this->selectedAttributes = [];
        $this->selectedVariation = null;
    }

    public function incrementQuantity(string $cartKey): void
    {
        if (!isset($this->cart[$cartKey])) {
            return;
        }

        $maxQty = $this->cart[$cartKey]['stock'] ?? 1;
        $qty = min($this->cart[$cartKey]['quantity'] + 1, $maxQty ?: 1);
        $this->cart[$cartKey]['quantity'] = $qty;
    }

    public function decrementQuantity(string $cartKey): void
    {
        if (!isset($this->cart[$cartKey])) {
            return;
        }

        $qty = max(1, $this->cart[$cartKey]['quantity'] - 1);
        $this->cart[$cartKey]['quantity'] = $qty;
    }

    public function setQuantity(string $cartKey, $value): void
    {
        if (!isset($this->cart[$cartKey])) {
            return;
        }

        $qty = max(1, (int) $value);
        $maxQty = $this->cart[$cartKey]['stock'] ?: $qty;
        $this->cart[$cartKey]['quantity'] = min($qty, $maxQty);
    }

    public function removeItem(string $cartKey): void
    {
        unset($this->cart[$cartKey]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->globalDiscountValue = 0;
        $this->shippingCharge = $this->insideDhakaCharge;
        $this->taxType = 'percent';
        $this->taxRate = 0;
        $this->transactionCode = '';
        $this->resetErrorBag(['cart']);
    }

    public function setDiscountType(string $type): void
    {
        if (!in_array($type, ['flat', 'percent'])) {
            return;
        }

        $this->globalDiscountType = $type;

        if ($type === 'percent' && $this->globalDiscountValue > 100) {
            $this->globalDiscountValue = 100;
        }
    }

    public function updatedGlobalDiscountValue($value): void
    {
        $value = max(0, (float) $value);

        if ($this->globalDiscountType === 'percent') {
            $value = min(100, $value);
        }

        $this->globalDiscountValue = $value;
    }

    public function updatedTaxRate($value): void
    {
        $value = max(0, (float) $value);

        if ($this->taxType === 'percent') {
            $value = min(100, $value);
        }

        $this->taxRate = $value;
    }

    public function setTaxType(string $type): void
    {
        if (!in_array($type, ['flat', 'percent'])) {
            return;
        }

        $this->taxType = $type;

        if ($type === 'percent' && $this->taxRate > 100) {
            $this->taxRate = 100;
        }
    }

    public function updatedShippingCharge($value): void
    {
        $this->shippingCharge = max(0, (float) $value);
    }

    public function useShippingPreset(string $preset): void
    {
        $this->shippingPreset = $preset;
        if ($preset === 'inside') {
            $this->shippingCharge = $this->insideDhakaCharge;
        } elseif ($preset === 'outside') {
            $this->shippingCharge = $this->outsideDhakaCharge;
        }
    }

    public function placeOrder(): void
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Add at least one product to the cart.');
            return;
        }

        $customerData = $this->resolveCustomerData();

        if (blank($customerData['name']) || blank($customerData['phone'])) {
            $this->addError('customer', 'Customer name & phone are required.');
            return;
        }

        $productIds = array_keys($this->cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $stockIssues = [];

        foreach ($this->cart as $item) {
            $product = $products[$item['product_id']] ?? null;

            if (!$product) {
                $stockIssues[] = 'Unknown product #' . $item['product_id'];
                continue;
            }

            // Check variation stock if applicable
            if (!empty($item['variation_id'])) {
                $variation = $product->variations->find($item['variation_id']);
                if (!$variation) {
                    $stockIssues[] = "Variation not found for {$product->name}";
                    continue;
                }
                if ($variation->stock < $item['quantity']) {
                    $stockIssues[] = "{$product->name} ({$item['options']}) - {$variation->stock} left";
                }
            } else {
                if ($product->stock_quantity < $item['quantity']) {
                    $stockIssues[] = "{$product->name} ({$product->stock_quantity} left)";
                }
            }
        }

        if (!empty($stockIssues)) {
            $this->addError('cart', 'Stock issue: ' . implode(', ', $stockIssues));
            return;
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $this->selectedCustomer['id'] ?? null,
                'customer_name' => $customerData['name'],
                'customer_phone_number' => $customerData['phone'],
                'customer_address' => $customerData['address'],
                'delivery_charge' => $this->shippingCharge,
                'discount' => $this->discountAmount,
                'subtotal' => $this->cartSubtotal,
                'total' => $this->grandTotal,
                'paid_amount' => $this->paymentStatus === 'paid' ? $this->grandTotal : 0,
                'payment_method' => $this->paymentMethod,
                'payment_status' => $this->paymentStatus,
                'payment_transaction_id' => $this->transactionCode ?: null,
                'source' => 'pos',
                'tax' => $this->taxAmount,
            ]);

            foreach ($this->cart as $item) {
                $product = $products[$item['product_id']];

                $order->items()->create([
                    'product_id' => $product->id,
                    'variation_id' => $item['variation_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                if (!empty($item['variation_id'])) {
                    $variation = $product->variations->find($item['variation_id']);
                    if ($variation) {
                        $variation->decrement('stock', $item['quantity']);
                    }
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            $this->clearCart();
            flashMessage('success', 'POS order placed successfully.');
            cache()->forget('admin_pos_stats');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('POS order failed', [
                'message' => $th->getMessage(),
            ]);
            $this->addError('cart', 'Could not place order. Please retry.');
        }
    }

    public function getCartSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getDiscountAmountProperty(): float
    {
        if ($this->globalDiscountType === 'percent') {
            return round(($this->cartSubtotal * $this->globalDiscountValue) / 100, 2);
        }

        return min($this->globalDiscountValue, $this->cartSubtotal);
    }

    public function getTaxAmountProperty(): float
    {
        $taxable = max(0, $this->cartSubtotal - $this->discountAmount);

        if ($this->taxType === 'percent') {
            return round(($taxable * $this->taxRate) / 100, 2);
        }

        return min($this->taxRate, $taxable);
    }

    public function getGrandTotalProperty(): float
    {
        $total = $this->cartSubtotal - $this->discountAmount + $this->taxAmount + $this->shippingCharge;

        return round(max(0, $total), 2);
    }

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function getRealtimeStatsProperty(): array
    {
        return cache()->remember('admin_pos_stats', 60, function () {
            return [
                'active_products' => Product::active()->count(),
                'low_stock' => Product::active()->where('stock_quantity', '<', 5)->count(),
                'today_orders' => Order::whereDate('created_at', now())->count(),
                'unique_customers' => Customer::count(),
            ];
        });
    }

    public function render()
    {
        $products = Product::query()
            ->with('category')
            ->when($this->categoryFilter !== 'all', fn($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->search, function ($q) {
                $keyword = trim($this->search);
                $q->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('sku', 'like', "%{$keyword}%");
                });
            })
            ->active()
            ->latest()
            ->paginate($this->perPage)->onEachSide(0);

        $categories = Category::active()->ordered()->get(['id', 'name']);

        return view('livewire.admin.pos-panel', [
            'products' => $products,
            'categories' => $categories,
            'stats' => $this->realtimeStats,
            'cartItems' => collect($this->cart),
            'grandTotal' => $this->grandTotal,
            'cartSubtotal' => $this->cartSubtotal,
            'discountAmount' => $this->discountAmount,
            'taxAmount' => $this->taxAmount,
            'cartCount' => $this->cartCount,
        ]);
    }

    protected function resolveCustomerData(): array
    {
        if ($this->selectedCustomer) {
            return [
                'name' => $this->selectedCustomer['name'] ?? '',
                'phone' => $this->selectedCustomer['phone'] ?? '',
                'address' => $this->selectedCustomer['address'] ?? '',
            ];
        }

        return [
            'name' => trim($this->customerForm['name'] ?? ''),
            'phone' => trim($this->customerForm['phone'] ?? ''),
            'address' => trim($this->customerForm['address'] ?? ''),
        ];
    }
}
