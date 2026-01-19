<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        if (!userCan('manage sliders')) { abort(403); }
        $sliders = Slider::all();
        return view('admin.pages.sliders.index', compact('sliders'));
    }

    public function create()
    {
        if (!userCan('manage sliders')) { abort(403); }
        return view('admin.pages.sliders.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage sliders')) { abort(403); }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|url',
        ]);

        $imagePath = uploadImage($request->image, 'slider');

        Slider::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        if (!userCan('manage sliders')) { abort(403); }
        return view('admin.pages.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        if (!userCan('manage sliders')) { abort(403); }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = uploadImage($request->image, 'slider');
            $slider->image = $imagePath;
        }

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        if (!userCan('manage sliders')) { abort(403); }
        deleteFile($slider->image);

        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
