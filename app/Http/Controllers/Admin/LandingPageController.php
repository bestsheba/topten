<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingPage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    public function index()
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        $landingPages = LandingPage::all();
        return view('admin.pages.landing-pages.index', compact('landingPages'));
    }

    public function create()
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        return view('admin.pages.landing-pages.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:landing_pages,url',
            'status' => 'nullable|in:on,1,true,0,false',
            'config' => 'nullable|string',
        ]);

        $config = null;
        if ($request->config) {
            try {
                $config = json_decode($request->config, true);
            } catch (\Exception $e) {
                return back()->withErrors(['config' => 'Invalid JSON format'])->withInput();
            }
        } else {
            // Use default config if none provided
            $config = \App\Helpers\LandingPageConfig::getDefaultConfig();
        }

        LandingPage::create([
            'title' => $request->title,
            'url' => Str::slug($request->url),
            'status' => $request->has('status') ? true : false,
            'config' => $config,
        ]);

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing page created successfully.');
    }

    public function edit(LandingPage $landingPage)
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        return view('admin.pages.landing-pages.edit', compact('landingPage'));
    }

    public function update(Request $request, LandingPage $landingPage)
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:landing_pages,url,' . $landingPage->id,
            'status' => 'nullable|in:on,1,true,0,false',
            'config' => 'nullable|string',
        ]);

        $config = null;
        if ($request->config) {
            try {
                $config = json_decode($request->config, true);
            } catch (\Exception $e) {
                return back()->withErrors(['config' => 'Invalid JSON format'])->withInput();
            }
        }

        $landingPage->update([
            'title' => $request->title,
            'url' => Str::slug($request->url),
            'status' => $request->has('status') ? true : false,
            'config' => $config,
        ]);

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing page updated successfully.');
    }

    public function destroy(LandingPage $landingPage)
    {
        if (!userCan('manage landing pages')) {
            abort(403);
        }
        $landingPage->delete();
        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing page deleted successfully.');
    }
}
