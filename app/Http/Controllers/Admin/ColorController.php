<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        $colors = Color::latest()->paginate(20);
        return view('admin.pages.color.index', compact('colors'));
    }

    public function create()
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        return view('admin.pages.color.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:colors,code',
            'hex_code' => 'nullable|string|max:7',
        ]);

        Color::create([
            'name' => $request->name,
            'code' => $request->code,
            'hex_code' => $request->hex_code,
            'is_active' => $request->has('active'),
        ]);

        flashMessage('success', 'Color Added Successfully.');
        return redirect()->route('admin.color.index');
    }

    public function edit(Color $color)
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        return view('admin.pages.color.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:colors,code,' . $color->id,
            'hex_code' => 'nullable|string|max:7',
        ]);

        $color->update([
            'name' => $request->name,
            'code' => $request->code,
            'hex_code' => $request->hex_code,
            'is_active' => $request->has('active'),
        ]);

        flashMessage('success', 'Color Updated Successfully.');
        return redirect()->route('admin.color.index');
    }

    public function destroy(Color $color)
    {
        if (!userCan('manage colors')) {
            abort(403);
        }
        $color->delete();
        flashMessage('success', 'Color Deleted Successfully.');
        return redirect()->route('admin.color.index');
    }
}
