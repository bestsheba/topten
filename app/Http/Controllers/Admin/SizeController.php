<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        if (!userCan('manage sizes')) { abort(403); }
        $sizes = Size::latest()->paginate(20);
        return view('admin.pages.size.index', compact('sizes'));
    }

    public function create()
    {
        if (!userCan('manage sizes')) { abort(403); }
        return view('admin.pages.size.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage sizes')) { abort(403); }
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sizes,code',
        ]);

        Size::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->has('active'),
        ]);

        flashMessage('success', 'Size Added Successfully.');
        return redirect()->route('admin.size.index');
    }

    public function edit(Size $size)
    {
        if (!userCan('manage sizes')) { abort(403); }
        return view('admin.pages.size.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        if (!userCan('manage sizes')) { abort(403); }
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sizes,code,' . $size->id,
        ]);

        $size->update([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->has('active'),
        ]);

        flashMessage('success', 'Size Updated Successfully.');
        return redirect()->route('admin.size.index');
    }

    public function destroy(Size $size)
    {
        if (!userCan('manage sizes')) { abort(403); }
        $size->delete();
        flashMessage('success', 'Size Deleted Successfully.');
        return redirect()->route('admin.size.index');
    }
}
