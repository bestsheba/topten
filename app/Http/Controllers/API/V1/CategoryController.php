<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    /**
     * Get paginated and filtered categories
     */
    public function index(Request $request)
    {
        try {
            // Pagination settings
            $per_page = $request->input('per_page', 30);
            $page = $request->input('page', 1);

            // Start with base query
            $query = Category::query();

            // Search by name or description
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function (Builder $q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Filter by parent category
            if ($request->has('parent_id')) {
                $query->where('parent_id', $request->input('parent_id'));
            }

            // Only top-level categories
            if ($request->boolean('top_level', false)) {
                $query->whereNull('parent_id');
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
                'products', 
                'subCategories', 
                'parentCategory'
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
                'error' => $e->getMessage(),
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get a single category by ID
     */
    public function show($id, Request $request)
    {
        try {
            // Start with base query
            $query = Category::where('id', $id);

            // Include related models if requested
            $with = $request->input('with', []);
            
            // Ensure with is an array
            $with = is_string($with) ? explode(',', $with) : $with;
            
            // Validate and filter allowed relationships
            $allowedRelationships = [
                'products', 
                'subCategories', 
                'parentCategory'
            ];
            
            $validWith = array_intersect($with, $allowedRelationships);
            
            if (!empty($validWith)) {
                $query->with($validWith);
            }

            // Fetch the category
            $category = $query->firstOrFail();

            // Additional computed or derived information
            $category->makeVisible([
                'description_full', 
                'meta_keywords', 
                'meta_description'
            ]);

            // Prepare response data
            $responseData = [
                'success' => true,
                'category' => $category,
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
                    'error' => 'Category not found',
                    'error_code' => 'CATEGORY_NOT_FOUND'
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
     * Get products for a specific category
     */
    public function products($categoryId, Request $request)
    {
        try {
            // Validate category exists
            $category = Category::findOrFail($categoryId);

            // Pagination settings
            $per_page = $request->input('per_page', 30);
            $page = $request->input('page', 1);

            // Start with base query for products in the category
            $query = Product::where('category_id', $categoryId);

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
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug
                ],
                'results' => $results,
                'filter' => $request->all(),
                'available_relationships' => $allowedRelationships
            ], 200);
        } catch (\Exception $e) {
            // Differentiate between not found and other errors
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Category not found',
                    'error_code' => 'CATEGORY_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }
}
