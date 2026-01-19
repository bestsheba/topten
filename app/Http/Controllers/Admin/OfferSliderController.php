<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferSlider;
use Illuminate\Http\Request;

class OfferSliderController extends Controller
{
    public function index()
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        $sliders = OfferSlider::all();
        return view('admin.pages.offer-slider.index', compact('sliders'));
    }

    public function create()
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        return view('admin.pages.offer-slider.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required',
            'link' => 'nullable|url',
        ]);

        $imagePath = uploadImage($request->image, 'offer-slider');

        OfferSlider::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.offer-slider.index')->with('success', 'Slider created successfully.');
    }

    public function edit(OfferSlider $offer_slider)
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        return view('admin.pages.offer-slider.edit', compact('offer_slider'));
    }

    public function update(Request $request, OfferSlider $offer_slider)
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable',
            'link' => 'nullable|url',
        ]);

        $image = $offer_slider->image;
        if ($request->hasFile('image')) {
            deleteFile($image);
            $imagePath = uploadImage($request->image, 'offer-slider');
            $image = $imagePath;
        }

        $offer_slider->update([
            'title' => $request->title,
            'image' => $image,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.offer-slider.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(OfferSlider $offer_slider)
    {
        if (!userCan('manage offer sliders')) {
            abort(403);
        }
        deleteFile($offer_slider->image);

        $offer_slider->delete();
        return redirect()->route('admin.offer-slider.index')->with('success', 'Slider deleted successfully.');
    }
}
