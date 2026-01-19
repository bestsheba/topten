<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use App\Models\MeasurementField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MeasurementFieldController extends Controller
{
     public function index()
    {
        $garments = GarmentType::with('measurementFields')->get();
        return view('admin.pages.measurements.fields.index', compact('garments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'garment_type_id' => 'required|exists:garment_types,id',
            'label' => 'required',
            'key' => 'required'
        ]);

        MeasurementField::create([
            'garment_type_id' => $request->garment_type_id,
            'label' => $request->label,
            'key' => Str::slug($request->key, '_'),
            'unit' => 'inch',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Measurement field added');
    }

    public function update(Request $request, MeasurementField $measurementField)
    {
        $measurementField->update([
            'label' => $request->label,
            'sort_order' => $request->sort_order,
        ]);

        return back()->with('success', 'Measurement field updated');
    }

    public function destroy(MeasurementField $measurementField)
    {
        $measurementField->delete();
        return back()->with('success', 'Measurement field deleted');
    }
}
