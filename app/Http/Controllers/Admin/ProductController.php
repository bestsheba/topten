<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Services\ProductSkuGenerateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::latest()
            ->with(['category', 'brand', 'variations'])
            ->when($request->filled('keyword'), function ($product) use ($request) {
                $product->where('name', 'LIKE', "%{$request->keyword}%");
            })
            ->paginate(20);

        // 🔢 Total Product Units (mix of simple + variation products)
        $totalProductUnit = Product::with('variations')->get()->sum(function ($product) {
            return $product->variations->count()
                ? $product->variations->sum('stock')     // variations exist
                : $product->stock_quantity;              // simple product
        });

        // 💰 Total Product Amount (price x stock)
        $totalProductAmount = Product::with('variations')->get()->sum(function ($product) {
            if ($product->variations->count()) {
                return $product->variations->sum(function ($variation) {
                    return $variation->price * $variation->stock;
                });
            } else {
                return $product->price * $product->stock_quantity;
            }
        });

        // 📦 Total Product Count (number of product records)
        $totalProductCount = Product::count();

        return view(
            'admin.pages.product.index',
            compact('products', 'totalProductUnit', 'totalProductAmount', 'totalProductCount')
        );
    }

    public function getSubcategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        return response()->json($subCategories);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        $sku = (new ProductSkuGenerateService())->generateUniqueSku('Random');
        $brands = Brand::active()->latest()->get();
        $sub_categories = SubCategory::where('category_id', $request->category)->get();

        return view('admin.pages.product.create', compact('categories', 'sku', 'brands', 'sub_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('scroll_height')) {
            return redirect()->route('admin.product.create', [
                'scroll' => $request->scroll_height,
                'category' => $request->category
            ])->withInput();
        }

        $request->validate([
            'name' => 'required',
            'picture' => 'required|image',
            'description' => 'required',
            'price' => 'required',
            'stock_quantity' => 'required',
            'sku' => 'required|unique:products,sku',
            'category' => 'required',
            // 'sub_category' => 'required',
            'brand' => 'nullable',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:51200',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|image|max:2048',
            'meta_keywords' => 'nullable|string|max:1000',
            'size_guide' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $picture = uploadImage($request->picture, 'products');
            $videoPath = '';
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('product_videos', 'public');
            }

            $metaImagePath = '';
            if ($request->hasFile('meta_image')) {
                $metaImagePath = $request->file('meta_image')->store('product_meta_images', 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'picture' => $picture,
                'description' => $request->description,
                'size_guide' => $request->size_guide,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $request->active ? true : false,
                'sku' => $request->sku,
                'category_id' => $request->category,
                'sub_category_id' => $request->sub_category,
                'brand_id' => $request->brand,
                'discount' => $request->discount,
                'discount_type' => $request->discount_type,
                'video' => $videoPath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_image' => $metaImagePath,
                'meta_keywords' => $request->meta_keywords,
            ]);
            DB::commit();
            flashMessage('success', 'Product Added Successfully.');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            deleteFile($picture);
            if (!empty($metaImagePath)) {
                deleteFile($metaImagePath);
            }
            info('product create error: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->all()
            ]);
            flashMessage('error', 'Something went wrong!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['variations.attributeValues.attribute', 'category', 'subCategory', 'brand', 'galleries']);
        $attributes = \App\Models\Attribute::with('values')->get();
        return view('admin.attributes.show', compact('product', 'attributes'));
    }
    public function details(Product $product)
    {
        $product->load('variations.attributeValues.attribute');

        // Group attribute values by attribute for variation selection
        $attributes = [];
        foreach ($product->variations as $variation) {
            foreach ($variation->attributeValues as $value) {
                $attributes[$value->attribute->name][$value->id] = $value->value;
            }
        }

        return view('admin.products.details', compact('product', 'attributes'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->latest()->get();
        $sub_categories = SubCategory::where('category_id', $request->category ?? $product->category_id)->get();

        return view('admin.pages.product.edit', compact('categories', 'product', 'brands', 'sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->filled('scroll_height')) {
            return redirect()->route('admin.product.edit', [$product->id, 'scroll' => $request->scroll_height, 'category' =>  $request->category])->withInput();
        }

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock_quantity' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'category' => 'required',
            // 'sub_category' => 'required',
            'brand' => 'nullable',
            'video' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|image|max:2048',
            'meta_keywords' => 'nullable|string|max:1000',
            'size_guide' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $picture = $product->picture;

            if ($request->hasFile('picture')) {
                deleteFile($picture);
                $picture = uploadImage($request->picture, 'products');
            }

            $videoPath = $product->video;

            if ($request->hasFile('video')) {
                if ($product->video && Storage::disk('public')->exists($product->video)) {
                    Storage::disk('public')->delete($product->video);
                }
                $videoPath = $request->file('video')->store('product_videos', 'public');
            }

            $metaImagePath = $product->meta_image;

            if ($request->hasFile('meta_image')) {
                if ($product->meta_image && Storage::disk('public')->exists($product->meta_image)) {
                    Storage::disk('public')->delete($product->meta_image);
                }
                $metaImagePath = $request->file('meta_image')->store('product_meta_images', 'public');
            }

            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'picture' => $picture,
                'description'  => $request->description,
                'size_guide' => $request->size_guide,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $request->active ? true : false,
                'sku' => $request->sku,
                'category_id' =>  $request->category,
                'sub_category_id' =>  $request->sub_category,
                'brand_id' =>  $request->brand,
                'discount' =>  $request->discount,
                'discount_type' =>  $request->discount_type,
                'video' => $videoPath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_image' => $metaImagePath,
                'meta_keywords' => $request->meta_keywords,
            ]);

            DB::commit();

            flashMessage('success', 'Product Updated Successfully.');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->hasFile('picture')) {
                deleteFile($picture);
            }
            if ($request->hasFile('meta_image')) {
                deleteFile($metaImagePath);
            }
            info('product update error: ' . $e->getMessage());
            flashMessage('error', $e->getMessage(), [
                'exception' => $e,
                'input' => $request->all()
            ]);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        deleteFile($product->picture);
        $product->delete();
        flashMessage('success', 'Product Deleted Successfully.');
        return redirect()->route('admin.product.index');
    }

    public function offerUpdate(Request $request, Product $product)
    {
        $product->update([
            'show_special_offer_list' => $request->offer ? true : false,
        ]);

        flashMessage('success', 'Updated...');
        return redirect()->back();
    }
}
