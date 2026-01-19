<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfferSlider;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private function addProductMetadata(&$products, array $favoriteProductIds = [])
    {
        $cart = session()->get('cart', []);

        foreach ($products as $product) {
            // Cart status
            $product->in_cart = isset($cart[$product->id]);

            // Favorite status
            $product->is_favorite = in_array($product->id, $favoriteProductIds, true);

            // Total sales
            $product->total_sales = DB::table('order_items')
                ->where('product_id', $product->id)
                ->sum('quantity');

            // Average rating and total ratings
            $ratingData = DB::table('reviews')
                ->where('product_id', $product->id)
                ->select(
                    DB::raw('AVG(star) as average_rating'),
                    DB::raw('COUNT(*) as total_ratings')
                )
                ->first();

            // Ensure default values
            $product->average_rating = $ratingData->total_ratings > 0 ? $ratingData->average_rating : 0;
            $product->total_ratings = $ratingData->total_ratings ?? 0;
        }
    }

    public function index()
    {
        $favoriteProductIds = [];
        if (Auth::check()) {
            try {
                $currentUser = Auth::user();
                if ($currentUser instanceof \App\Models\User) {
                    $favoriteProductIds = $currentUser->favorites()->pluck('products.id')->toArray();
                }
            } catch (\Throwable $th) {
                $favoriteProductIds = [];
            }
        }

        $data['categories'] = Category::active()->ordered()->with(['products' => function ($query) {
            $query->active()->take(8)->with('category');
        }])->get();

        // Get cart from session
        $cart = session()->get('cart', []);

        // Loop through categories and products to mark the in_cart flag
        foreach ($data['categories'] as $category) {
            foreach ($category->products as $product) {
                // Check if the product exists in the cart
                $product->in_cart = isset($cart[$product->id]);

                // Favorite status
                $product->is_favorite = in_array($product->id, $favoriteProductIds, true);

                // Total sales
                $product->total_sales = DB::table('order_items')
                    ->where('product_id', $product->id)
                    ->sum('quantity');

                // Average rating and total ratings
                $ratingData = DB::table('reviews')
                    ->where('product_id', $product->id)
                    ->select(
                        DB::raw('AVG(star) as average_rating'),
                        DB::raw('COUNT(*) as total_ratings')
                    )
                    ->first();

                // Ensure default values
                $product->average_rating = $ratingData->total_ratings > 0 ? $ratingData->average_rating : 0;
                $product->total_ratings = $ratingData->total_ratings ?? 0;
            }
        }

        $data['brands'] = Brand::active()->latest()->get();
        $data['top_categories'] = Category::active()->ordered()->take(10)->get();
        $data['sliders'] = Slider::get();

        $popularProducts = Product::select(
            'products.id',
            'products.name',
            'products.slug',
            'products.price',
            'products.picture',
            'products.category_id',
            'products.discount',
            'products.discount_type',
            DB::raw('COUNT(order_items.product_id) as total_sales')
        )
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.price', 'products.picture', 'products.category_id', 'products.discount', 'products.discount_type')
            ->orderByDesc('total_sales')
            ->limit(15)
            ->with('category')
            ->get();

        // Add metadata to popular products
        $this->addProductMetadata($popularProducts, $favoriteProductIds);
        $data['popularProducts'] = $popularProducts;

        $flashDealProducts = Product::active()->offer()->latest()->withCount('sold')->with('category')->take(10)->get();

        // Add metadata to flash deal products
        $this->addProductMetadata($flashDealProducts, $favoriteProductIds);
        $data['flash_deal_products'] = $flashDealProducts;

        $data['show_flash_deal'] = Setting::first()?->show_flash_deal;
        $data['flash_deal_timer'] = Setting::first()?->flash_deal_timer;

        $data['offer_banners'] = OfferSlider::all(['image', 'link']);

        return view('frontend.pages.index', $data);
    }

    public function productDetails($slug = null)
    {
        $product =  Product::active()->with(['galleries', 'category', 'brand'])->where('slug', $slug)
            ->first();

        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart', []);
        $product->in_cart = isset($cart[$product->id]);
        $product->cart_quantity = $cart[$product->id]['quantity'] ?? 1;

        // Favorite status for main product
        if (Auth::check()) {
            try {
                $currentUser = Auth::user();
                if ($currentUser instanceof \App\Models\User) {
                    $favoriteIds = $currentUser->favorites()->pluck('products.id')->toArray();
                    $product->is_favorite = in_array($product->id, $favoriteIds, true);
                } else {
                    $product->is_favorite = false;
                }
            } catch (\Throwable $th) {
                $product->is_favorite = false;
            }
        } else {
            $product->is_favorite = false;
        }

        // Calculate total sales for this product
        $totalSales = DB::table('order_items')
            ->where('product_id', $product->id)
            ->sum('quantity');

        // Load reviews with user
        $reviews = $product->reviews()->with('user')->latest()->paginate(5);

        // Calculate average rating and total ratings
        $ratingData = DB::table('reviews')
            ->where('product_id', $product->id)
            ->select(
                DB::raw('AVG(star) as average_rating'),
                DB::raw('COUNT(*) as total_ratings')
            )
            ->first();

        $averageRating = $ratingData->average_rating ?? 0;
        $totalRatings = $ratingData->total_ratings ?? 0;

        $related_products = Product::active()->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Exclude current product
            ->inRandomOrder()
            ->take(15)
            ->with('category')
            ->get();
        
        // Add cart and metadata to related products
        $favoriteIdsForRelated = [];
        if (Auth::check()) {
            try {
                $currentUser = Auth::user();
                if ($currentUser instanceof \App\Models\User) {
                    $favoriteIdsForRelated = $currentUser->favorites()->pluck('products.id')->toArray();
                }
            } catch (\Throwable $th) {
                $favoriteIdsForRelated = [];
            }
        }
        $this->addProductMetadata($related_products, $favoriteIdsForRelated);
        
        foreach ($related_products as $related_product) {
            $related_product->in_cart = isset($cart[$related_product->id]);
        }
        $product->load('variations.attributeValues.attribute');
        $attributes = [];
        foreach ($product->variations as $variation) {
            foreach ($variation->attributeValues as $value) {
                $attributes[$value->attribute->name][$value->id] = $value->value;
            }
        }
        return view('frontend.pages.product-details', compact('product', 'related_products', 'attributes', 'reviews', 'averageRating', 'totalSales', 'totalRatings'));
    }

    public function shop(Request $request)
    {
        $query = Product::query()->active();
        if ($request->has('sort')) {
            switch ($request->input('sort')) {
                case '1':
                    $query->orderBy('price', 'asc');
                    break;
                case '2':
                    $query->orderBy('price', 'desc');
                    break;
                case '3':
                    $query->latest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        $query->when($request->has('categories') && $request->categories != null, function ($product) use ($request) {
            $product->whereIn('category_id', $request->categories);
        })
            ->when($request->has('sub_categories') && $request->sub_categories != null, function ($product) use ($request) {
                $product->whereIn('sub_category_id', $request->sub_categories);
            })
            ->when($request->has('brands') && $request->brands != null, function ($product) use ($request) {
                $product->whereIn('brand_id', $request->brands);
            })
            ->when($request->has('min_price') && $request->min_price != null, function ($product) use ($request) {
                $product->where('price', '>=', $request->min_price);
            })
            ->when($request->has('max_price') && $request->max_price != null, function ($product) use ($request) {
                $product->where('price', '<=', $request->max_price);
            })
            ->when($request->has('offer') && $request->offer != null, function ($product) use ($request) {
                $product->where('show_special_offer_list', 1);
            });
        $products = $query->with('category')->paginate(18)->onEachSide(0);

        // Add metadata to products
        $this->addProductMetadata($products);

        $brands = Brand::active()->withCount('products')->latest()->get();
        $categories = Category::with(['subCategories' => function ($query) {
            $query->active()->withCount('products');
        }])->active()->withCount('products')->ordered()->get();

        $offer_product_count = Product::active()->where('show_special_offer_list', 1)->count();

        return view('frontend.pages.shop', compact('products', 'brands', 'categories', 'offer_product_count'));
    }


    public function customPage($slug = null)
    {
        $page = CustomPage::where('slug', $slug)->first();

        // If the request comes from AJAX (modal fetch), return only page description/body
        if (request()->ajax()) {
            return response($page?->description ?? '', 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }

        return view('frontend.pages.custom-page', ['page' => $page]);
    }

    public function searchAutoComplete(Request $request)
    {
        try {
            $data = Product::select('name as value', 'id', 'slug')
                ->where('name', 'LIKE', '%' . $request->keywords . '%')
                ->active()
                ->latest()
                ->take(8)
                ->get()
                ->map(function ($product) {
                    return [
                        'value' => $product->value,
                        'id' => $product->id,
                        'url' => route('product.details', $product->slug),
                    ];
                });

            return $data;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
