<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!userCan('view brands')) {
            abort(403);
        }
        $brands = Brand::latest()
            ->when($request->has('keyword') && $request->keyword != null, function ($category) use ($request) {
                $category->where('name', 'LIKE', "%$request->keyword%");
            })
            ->paginate(20);

        return view('admin.pages.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!userCan('create brand')) {
            abort(403);
        }
        return view('admin.pages.brand.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('create brand')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'picture' => 'required',
        ]);

        $picture = uploadImage($request->picture, 'brand');

        Brand::create([
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Brands Created Successfully.');
        return redirect()->route('admin.brand.index');
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
    public function edit(Request $request, Brand $brand)
    {
        if (!userCan('edit brand')) {
            abort(403);
        }
        return view('admin.pages.brand.index', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        if (!userCan('edit brand')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
        ]);

        $picture = $brand->picture;
        if ($request->hasFile('picture')) {
            deleteFile($brand->picture);
            $picture = uploadImage($request->picture, 'brand');
        }

        $brand->update([
            'name' => $request->name,
            'picture' => $picture,
            'is_active' => $request->active ? true : false,
        ]);

        flashMessage('success', 'Brand Updated Successfully.');
        return redirect()->route('admin.brand.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if (!userCan('delete brand')) {
            abort(403);
        }
        deleteFile($brand->picture);

        $brand->delete();

        flashMessage('success', 'Brand Deleted Successfully.');
        return redirect()->route('admin.brand.index');
    }
}
