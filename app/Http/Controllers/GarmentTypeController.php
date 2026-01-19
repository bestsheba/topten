<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use Illuminate\Http\Request;

class GarmentTypeController extends Controller
{
   public function index()
    {
        $types = GarmentType::paginate(10);
        return view('admin.pages.measurements.types.index', compact('types'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        GarmentType::create([
            'name' => $request->input('name'),
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Garment type created successfully.');
    }

    public function update(Request $request, GarmentType $garmentType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:garment_types,name,' . $garmentType->id,
        ]);

        $garmentType->update($request->only('name'));

        return back()->with('success', 'Garment type updated successfully.');
    }

    public function destroy(GarmentType $garmentType)
    {
        if ($garmentType->expenses()->exists()) {
            return back()->with('error', 'Cannot delete type that is in use.');
        }

        $garmentType->delete();
        return back()->with('success', 'Garment type deleted successfully.');
    }
}
