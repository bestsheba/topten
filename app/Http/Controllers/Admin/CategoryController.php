<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!userCan('view categories')) {
            abort(403);
        }
        $categories = Category::ordered()
            ->when($request->has('keyword') && $request->keyword != null, function ($category) use ($request) {
                $category->where('name', 'LIKE', "%$request->keyword%");
            })
            ->paginate(20);

        return view('admin.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!userCan('create category')) {
            abort(403);
        }
        return view('admin.pages.category.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('create category')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'picture' => 'required',
        ]);

        $picture = uploadImage($request->picture, 'category');

        Category::create([
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Category Added Successfully.');
        return redirect()->route('admin.category.index');
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
    public function edit(Request $request, Category $category)
    {
        if (!userCan('edit category')) {
            abort(403);
        }
        $categories = Category::ordered()
            ->when($request->has('keyword') && $request->keyword != null, function ($category) use ($request) {
                $category->where('name', 'LIKE', "%$request->keyword%");
            })
            ->paginate(20);

        return view('admin.pages.category.index', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if (!userCan('edit category')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
        ]);

        $picture = $category->picture;
        if ($request->hasFile('picture')) {
            deleteFile($category->picture);
            $picture = uploadImage($request->picture, 'category');
        }

        $category->update([
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Category Updated Successfully.');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!userCan('delete category')) {
            abort(403);
        }
        deleteFile($category->picture);

        $category->delete();

        flashMessage('success', 'Category Deleted Successfully.');
        return redirect()->route('admin.category.index');
    }
}
