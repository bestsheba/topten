<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use App\Models\MeasurementField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TailorOrderController extends Controller
{
   public function create()
    {
        $garmentTypes = GarmentType::all();
        return view('admin.tailors.orders.create', compact('garmentTypes'));
    }

    public function measurements($id)
    {
        return MeasurementField::where('garment_type_id', $id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'garment_type_id' => 'required',
            'measurements' => 'required|array',
        ]);

        $order = TailorOrder::create($request->only(
            'customer_name',
            'phone',
            'garment_type_id'
        ));

        foreach ($request->measurements as $key => $value) {
            $order->measurements()->create([
                'key' => $key,
                'value' => $value
            ]);
        }

        return redirect()->back()->with('success', 'Order Created Successfully');
    }
}
