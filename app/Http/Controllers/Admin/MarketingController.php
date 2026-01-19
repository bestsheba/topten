<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketingController extends Controller
{
    public function setupSeo()
    {
        if (!userCan('manage seo')) {
            abort(403);
        }
        return view('admin.pages.marketing.seo');
    }

    public function metaPixel()
    {
        if (!userCan('manage meta pixel')) {
            abort(403);
        }
        return view('admin.pages.marketing.meta-pixel');
    }

    public function updateSeo(Request $request)
    {
        if (!userCan('manage seo')) {
            abort(403);
        }
        $request->validate([
            'seo_title' => 'required|string|max:255',
            'seo_description' => 'required|string|max:255',
            'seo_keywords' => 'required|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $settings = Setting::first();

        $image = $settings->seo_banner_image;
        if ($request->hasFile('banner_image')) {
            $image = uploadImage($request->file('banner_image'), 'seo');
        }

        $settings->seo_title = $request->seo_title;
        $settings->seo_description = $request->seo_description;
        $settings->seo_keywords = $request->seo_keywords;
        $settings->seo_banner_image = $image;
        $settings->save();

        return redirect()->back()->with('success', 'SEO updated successfully');
    }

    public function updateMetaPixel(Request $request)
    {
        if (!userCan('manage meta pixel')) {
            abort(403);
        }
        $request->validate([
            'meta_pixel' => 'nullable|string',
        ]);

        $settings = Setting::first();
        $settings->meta_pixel = $request->meta_pixel;
        $settings->save();

        return redirect()->back()->with('success', 'Meta Pixel updated successfully');
    }

    public function googleTagManager()
    {
        if (!userCan('manage meta pixel')) {
            abort(403);
        }
        return view('admin.pages.marketing.google-tag-manager');
    }

    public function updateGoogleTagManager(Request $request)
    {
        if (!userCan('manage meta pixel')) {
            abort(403);
        }
        $request->validate([
            'google_tag_manager_id' => 'nullable|string|max:40',
        ]);

        $settings = Setting::first();
        $settings->google_tag_manager_id = $request->google_tag_manager_id;
        $settings->save();

        return redirect()->route('admin.google-tag-manager')->with('success', 'Google Tag Manager updated successfully');
    }
}
