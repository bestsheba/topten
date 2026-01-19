<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!userCan('manage gallery')) {
            abort(403);
        }
        $galleries = Gallery::latest()->where('product_id', $request->product)->paginate(20)->onEachSide(0);

        return view('admin.pages.product.gallery', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('manage gallery')) {
            abort(403);
        }
        $request->validate([
            'product' => 'required',
            'picture' => 'required|image',
        ]);

        $picture = uploadImage($request->picture, 'gallery');

        Gallery::create([
            'product_id' => $request->product,
            'path' => $picture,
        ]);

        flashMessage('success', 'Gallery Added Successfully.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        if (!userCan('manage gallery')) {
            abort(403);
        }
        deleteFile($gallery->path);

        $gallery->delete();

        flashMessage('success', 'Gallery Deleted Successfully.');
        return redirect()->back();
    }
}
