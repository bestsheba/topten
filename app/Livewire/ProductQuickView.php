<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariation;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class ProductQuickView extends Component
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

    public function mount() {}

    #[On('open-quick-view')]
    public function openModal(Product $product)
    {
        // Load product with its relations
        $product->load([
            'variations.attributeValues.attribute',
            'galleries'
        ]);

        $this->modalOpen = true;
        $this->product = $product;
        $this->variations = $product->variations;
        $this->setGalleries($product);
        $this->prepareAttributes($product);
        $this->quantity = 1;

        // Automatically select first variation if exists
        $this->selectedVariation = $this->variations->sortBy('price')->first();

        $this->product_variations_min_price = $this->variations->sortBy('price')->first()?->price ?? 0;
        $this->product_variations_max_price = $this->product->variations->max('price');

        $this->dispatch('off-spinner');
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

    public function addToCartAction()
    {
        $cart = session()->get('cart', []);
        $cartKey = $this->product->id . ':' . $this->selectedVariation?->id;

        if (isset($cart[$cartKey])) {
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

        $this->closeModal();
        $this->dispatch('update-cart-icon');
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
    public function setGalleries($product)
    {
        // Collect images from product variations and galleries
        $variationImages = $product->variations
            ->filter(function ($variation) {
                return $variation->image !== null && $variation->image !== '';
            })
            ->map(function ($variation) {
                return asset('storage/' . $variation->image);
            })
            ->toArray();

        $galleryImages = $product->galleries
            ->filter(function ($gallery) {
                return $gallery->picture_url !== null && $gallery->picture_url !== '';
            })
            ->pluck('picture_url')
            ->toArray();

        $this->images = array_values(array_unique(
            array_merge($variationImages, $galleryImages)
        ));

        // Fallback to product picture if no images
        if (empty($this->images) && $product->picture) {
            $this->images[] = asset('/' . $product->picture);
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->product = null;
        $this->selectedVariation = null;
        $this->quantity = 1;
        $this->images = [];
    }

    use \Livewire\WithPagination;

    public $rating = 0;
    public $comment = '';
    public $showReviewForm = false;

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3',
        ]);

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user already reviewed this product
        $existingReview = \App\Models\Review::where('user_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingReview) {
            $this->addError('rating', 'You have already reviewed this product.');
            return;
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
            'star' => $this->rating,
            'comment' => $this->comment,
            'status' => 'active', // Assuming default status
        ]);

        $this->reset(['rating', 'comment']);
        session()->flash('message', 'Review submitted successfully!');
    }

    public function render()
    {
        $reviews = null;
        $related_products = collect();
        $averageRating = 0;
        $totalRatings = 0;

        if ($this->product) {
            $reviews = $this->product->reviews()->with('user')->latest()->paginate(5);
            $related_products = Product::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->take(10)
                ->get();

            $averageRating = $this->product->reviews()->avg('star') ?? 0;
            $totalRatings = $this->product->reviews()->count();
        }

        return view('livewire.product-quick-view', [
            'reviews' => $reviews,
            'related_products' => $related_products,
            'averageRating' => $averageRating,
            'totalRatings' => $totalRatings
        ]);
    }
}
