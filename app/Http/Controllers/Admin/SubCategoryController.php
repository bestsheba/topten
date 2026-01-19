<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!userCan('view categories') && !userCan('manage subcategories')) { abort(403); }
        $sub_categories = SubCategory::with(['category'])->latest()
            ->when($request->has('keyword') && $request->keyword != null, function ($category) use ($request) {
                $category->where('name', 'LIKE', "%$request->keyword%");
            })
            ->paginate(20)->withQueryString();

        return view('admin.pages.category.sub-category', compact('sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!userCan('create category') && !userCan('manage subcategories')) { abort(403); }
        $categories = Category::active()->ordered()->get();

        return view('admin.pages.category.sub-category', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('create category') && !userCan('manage subcategories')) { abort(403); }
        $request->validate([
            'name' => 'required',
            'picture' => 'required',
            'category' => 'required',
        ]);

        $picture = uploadImage($request->picture, 'sub-category');

        SubCategory::create([
            'category_id' => $request->category,
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Sub Category Added Successfully.');
        return redirect()->route('admin.sub-category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $sub_category)
    {
        if (!userCan('edit category') && !userCan('manage subcategories')) { abort(403); }
        $categories = Category::active()->ordered()->get();

        return view('admin.pages.category.sub-category', compact('categories', 'sub_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $sub_category)
    {
        if (!userCan('edit category') && !userCan('manage subcategories')) { abort(403); }
        $request->validate([
            'name' => 'required',
        ]);

        $picture = $sub_category->picture;
        if ($request->hasFile('picture')) {
            deleteFile($sub_category->picture);
            $picture = uploadImage($request->picture, 'sub-category');
        }

        $sub_category->update([
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Category Updated Successfully.');
        return redirect()->route('admin.sub-category.index', ['category' => request('category')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $sub_category)
    {
        if (!userCan('delete category') && !userCan('manage subcategories')) { abort(403); }
        deleteFile($sub_category->picture);

        $sub_category->delete();

        flashMessage('success', 'Sub Category Deleted Successfully.');
        return redirect()->route('admin.sub-category.index', ['category' => request('category')]);
    }
}
