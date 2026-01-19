<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    /**
     * Get paginated and filtered products
     */
    public function index(Request $request)
    {
        try {
            // Pagination settings
            $per_page = $request->input('per_page', 30);
            $page = $request->input('page', 1);

            // Start with base query
            $query = Product::query();

            // Filter by category
            if ($request->has('category_id')) {
                $query->where('category_id', $request->input('category_id'));
            }

            // Filter by brand
            if ($request->has('brand_id')) {
                $query->where('brand_id', $request->input('brand_id'));
            }

            // Filter by price range
            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            // Search by name or description
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function (Builder $q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Include related models if requested
            $with = $request->input('with', []);

            // Ensure with is an array
            $with = is_string($with) ? explode(',', $with) : $with;

            // Validate and filter allowed relationships
            $allowedRelationships = [
                'brand',
                'category',
                'subCategory',
                'galleries',
                'sold',
                'variations'
            ];

            $validWith = array_intersect($with, $allowedRelationships);

            if (!empty($validWith)) {
                $query->with($validWith);
            }

            // Paginate results
            $results = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'results' => $results,
                'filter' => $request->all(),
                'available_relationships' => $allowedRelationships
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single product by ID
     */
    public function show($id, Request $request)
    {
        try {
            // Start with base query
            $query = Product::where('id', $id);

            // Include related models if requested
            $with = $request->input('with', []);

            // Ensure with is an array
            $with = is_string($with) ? explode(',', $with) : $with;

            // Validate and filter allowed relationships
            $allowedRelationships = [
                'brand',
                'category',
                'subCategory',
                'galleries',
                'sold',
                'variations'
            ];

            $validWith = array_intersect($with, $allowedRelationships);

            if (!empty($validWith)) {
                $query->with($validWith);
            }

            // Fetch the product
            $product = $query->firstOrFail();

            // Additional computed or derived information
            $product->makeVisible([
                'description_full',
                'additional_details',
                'meta_keywords',
                'meta_description'
            ]);

            // Prepare response data
            $responseData = [
                'success' => true,
                'product' => $product,
                'available_relationships' => $allowedRelationships
            ];

            // Add related models to response if included
            if (!empty($validWith)) {
                $responseData['included_relationships'] = $validWith;
            }

            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            // Differentiate between not found and other errors
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Product not found',
                    'error_code' => 'PRODUCT_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Search products by keyword
     */
    public function search(Request $request)
    {
        // Validation for keyword
        $request->validate([
            'keyword' => 'required|string|min:2',
            'per_page' => 'integer|min:1|max:100',
        ]);


        try {

            // Pagination settings
            $per_page = $request->input('per_page', 30);

            // Start with base query
            $query = Product::query();

            // Search by keyword in name or description
            $keyword = $request->input('keyword');
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            });

            // Optional sorting
            $sortBy = $request->input('sort_by', 'relevance');
            if ($sortBy === 'relevance') {
                // Basic relevance sorting by putting exact matches first
                $query->orderByRaw("CASE 
                    WHEN name LIKE ? THEN 1 
                    WHEN description LIKE ? THEN 2 
                    ELSE 3 
                END", [
                    "%{$keyword}%",
                    "%{$keyword}%"
                ]);
            } else {
                $sortOrder = $request->input('sort_order', 'desc');
                $query->orderBy($sortBy, $sortOrder);
            }

            // Include optional relationships
            $with = $request->input('with', []);
            $with = is_string($with) ? explode(',', $with) : $with;

            $allowedRelationships = [
                'brand',
                'category',
                'subCategory',
                'galleries',
                'sold',
                'variations'
            ];

            $validWith = array_intersect($with, $allowedRelationships);

            if (!empty($validWith)) {
                $query->with($validWith);
            }

            // Paginate results
            $results = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'results' => $results,
                'keyword' => $keyword,
                'total_results' => $results->total(),
                'available_relationships' => $allowedRelationships
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'SEARCH_ERROR'
            ], 500);
        }
    }
}
