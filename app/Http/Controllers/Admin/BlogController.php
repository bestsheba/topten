<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!userCan('view blogs')) {
            abort(403);
        }
        $blogs = Blog::latest()->paginate(20);

        return view('admin.pages.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!userCan('create blog')) {
            abort(403);
        }
        return view('admin.pages.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('create blog')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title',
            'description' => 'required|string',
            'thumbnail' => 'required|image',
        ]);

        $picture = uploadImage($request->thumbnail, 'blog');

        Blog::create([
            'title' => $validatedData['title'],
            'body' => $validatedData['description'],
            'thumbnail' => $picture,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog created successfully!');
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
    public function edit(Blog $blog)
    {
        if (!userCan('edit blog')) {
            abort(403);
        }
        return view('admin.pages.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        if (!userCan('edit blog')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title,' . $blog->id,
            'description' => 'required|string',
        ]);

        $picture = $blog->thumbnail;
        if ($request->hasFile('thumbnail')) {

            $picture = uploadImage($request->thumbnail, 'blog');
        }

        $blog->update([
            'title' => $validatedData['title'],
            'body' => $validatedData['description'],
            'thumbnail' => $picture,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if (!userCan('delete blog')) {
            abort(403);
        }
        deleteFile($blog->thumbnail);

        $blog->delete();

        flashMessage('success', 'Blog Deleted Successfully.');
        return redirect()->route('admin.blog.index');
    }
}
