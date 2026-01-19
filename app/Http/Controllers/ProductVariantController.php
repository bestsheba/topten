<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function generateVariations(Request $request, Product $product)
    {
        $request->validate([
            'attributes' => 'required|array',
            'attributes.*' => 'exists:attributes,id',
            'values' => 'required|array',
            'values.*' => 'exists:attribute_values,id'
        ]);

        // Initialize selectedAttributes properly
        $selectedAttributes = Attribute::with(['values' => function ($query) use ($request) {
            $query->whereIn('id', $request->input('values', []));
        }])->whereIn('id', $request->input('attributes', []))->get();

        $variations = $this->generateCombinations($selectedAttributes);

        return view('admin.attributes.variations', compact('product', 'variations', 'selectedAttributes'));
    }


    public function storeVariations(Request $request, Product $product)
    {
        $request->validate([
            'variations' => 'required|array',
            'variations.*.price' => ['required', 'numeric', 'min:0', 'max:' . $product->price],
            'variations.*.stock' => 'required|integer|min:0',
            'variations.*.attribute_values' => 'required|array',
            'variations.*.attribute_values.*' => 'exists:attribute_values,id'
        ], [
            'variations.*.price.max' => 'The variation price cannot exceed the base price of ' . number_format($product->price, 2) . ' ৳'
        ]);

        foreach ($request->variations as $variationData) {
            $variation = $product->variations()->create([
                'sku' => $product->id . '-' . Str::random(6),
                'price' => $variationData['price'] ?? $product->price,
                'stock' => $variationData['stock'],
                'image' => $this->handleImageUpload($variationData['image'] ?? null)
            ]);

            $variation->attributeValues()->sync($variationData['attribute_values']);
        }

        return redirect()->route('admin.product.show', $product)
            ->with('success', 'Variations created successfully!');
    }

    public function updateVariations(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'variations' => 'required|array',
                'variations.*' => 'required|array',
                'variations.*.price' => ['required', 'numeric', 'min:0', 'max:' . $product->price],
                'variations.*.stock' => 'required|integer|min:0',
                'variations.*.image' => 'nullable|image'
            ], [
                'variations.*.price.max' => 'The variation price cannot exceed the base price of ' . number_format($product->price, 2) . ' ৳'
            ]);

            DB::beginTransaction();

            foreach ($request->variations as $variationId => $variationData) {
                try {
                    $variation = ProductVariation::findOrFail($variationId);

                    $updateData = [
                        'price' => $variationData['price'] ?? $product->price,
                        'stock' => $variationData['stock']
                    ];

                    if (isset($variationData['image'])) {
                        $updateData['image'] = $this->handleImageUpload($variationData['image']);
                    }

                    $variation->update($updateData);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Failed to update variation ID {$variationData['id']}: " . $e->getMessage());
                    return redirect()->back()
                        ->with('error', "Failed to update variation. Please try again.");
                }
            }

            DB::commit();

            return redirect()->route('admin.product.show', $product)
                ->with('success', 'Variations updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::error('Validation failed when updating product variations', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product variation update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'product_id' => $product->id,
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function destroyVariation(Product $product, ProductVariation $variation)
    {
        $variation->delete();
        return redirect()->route('admin.product.show', $product)
        ->with('success', 'Variation deleted successfully!');
    }

    private function generateCombinations($attributes)
    {
        if ($attributes->isEmpty()) {
            return [];
        }

        $result = [[]];

        foreach ($attributes as $attribute) {
            $tmp = [];
            foreach ($result as $item) {
                foreach ($attribute->values as $value) {
                    $tmp[] = array_merge($item, [$attribute->id => $value]);
                }
            }
            $result = $tmp;
        }

        return $result;
    }


    private function handleImageUpload($image)
    {
        if (!$image) return null;
        return $image->store('variation_images', 'public');
    }
}
