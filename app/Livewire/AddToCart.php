<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariation;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AddToCart extends Component
{
    public bool $modalOpen = false;
    public ?Product $product = null;
    public array $images = [];
    public $variations;
    public ?ProductVariation $selectedVariation = null;
    public int $quantity = 1;
    public array $productAttributes = [];
    public $product_variations_min_price = 0;
    public $product_variations_max_price = 0;

    public function mount(Product $product)
    {
        // Load product with its relations
        $product->load([
            'variations.attributeValues.attribute',
            'galleries'
        ]);

        $this->modalOpen = true;
        $this->product = $product;
        $this->variations = $product->variations;
        $this->prepareAttributes($product);
        $this->quantity = 1;

        // Automatically select first variation if exists
        $this->selectedVariation = $this->variations->sortBy('price')->first();

        $this->product_variations_min_price = $this->variations->sortBy('price')->first()?->price ?? 0;
        $this->product_variations_max_price = $this->product->variations->max('price');
    }

    public function prepareAttributes($product)
    {
        // Sort variations by price (lowest first)
        $sortedVariations = $product->variations->sortBy('price');

        // Collect unique attributes for the product
        $this->productAttributes = $sortedVariations
            ->flatMap(function ($variation) {
                return $variation->attributeValues->map(function ($attributeValue) use ($variation) {
                    return [
                        'attribute' => $attributeValue->attribute->name,
                        'value' => $attributeValue->value,
                        'id' => $attributeValue->id,
                        'variation' => $attributeValue->pivot?->product_variation_id,
                        'price' => $variation->price // Include price for sorting
                    ];
                });
            })
            ->groupBy('attribute')
            ->map(function ($values) {
                // Sort by price and get unique values
                return $values->sortBy('price')
                    ->unique('value')
                    ->values()
                    ->map(function ($item) {
                        // Remove price from final output if not needed
                        unset($item['price']);
                        return $item;
                    });
            })
            ->toArray();
    }

    public function selectVariation(int $variationId)
    {
        $this->selectedVariation = $this->variations->firstWhere('id', $variationId);
        $this->product_variations_min_price = $this->selectedVariation->price;

        // Update images if variation has specific image
        if ($this->selectedVariation && $this->selectedVariation->image) {
            $this->images = [
                asset('storage/' . $this->selectedVariation->image),
                ...$this->product->galleries->pluck('picture_url')->toArray()
            ];
        }
    }

    public function updateQuantity(int $change)
    {
        $this->quantity = max(1, $this->quantity + $change);
    }

    public function isProductInCart(): bool
    {
        $cart = session()->get('cart', []);
        $cartKey = $this->product->id . ':' . $this->selectedVariation?->id;

        return isset($cart[$cartKey]);
    }

    public function addToCartAction($buy = false)
    {
        $cart = session()->get('cart', []);
        $cartKey = $this->product->id . ':' . $this->selectedVariation?->id;

        if (isset($cart[$cartKey]) && !$buy) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        } else {
            $cart[$cartKey] = [
                'product_id' => $this->product->id,
                'variation_id' => $this->selectedVariation?->id,
                'quantity' => $this->quantity ?? 1,
            ];
            session()->put('cart', $cart);
        }
        $this->dispatch('update-cart-icon');

        if ($buy) {
            return redirect()->route('checkout');
        }

        // $this->dispatch('open-cart-drawer');
    }
    public function orderNow()
    {
        $cart = session()->get('cart', []);
        $cartKey = $this->product->id . ':' . ($this->selectedVariation?->id ?? 'default');

        // Always add/update the product in the cart
        $cart[$cartKey] = [
            'product_id' => $this->product->id,
            'variation_id' => $this->selectedVariation?->id,
            'quantity' => $this->quantity ?? 1,
        ];

        session()->put('cart', $cart);

        // Update the cart icon (Livewire event)
        $this->dispatch('update-cart-icon');

        // Redirect directly to checkout page
        return redirect()->route('checkout');
    }
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
