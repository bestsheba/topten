<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use App\Models\MeasurementField;
use App\Models\Tailor;
use App\Models\TailorOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TailorOrderController extends Controller
{
    public function index()
    {
        $orders = TailorOrder::with(['customer', 'tailor', 'garmentType'])
            ->latest()
            ->paginate(10);

        return view('admin.tailors.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $tailorOrder = TailorOrder::with(['customer', 'tailor', 'garmentType'])->findOrFail($id);
        return view('admin.tailors.orders.show', [
            'order' => $tailorOrder
        ]);
    }

    public function create()
    {
        $garmentTypes = GarmentType::all();
        $customers = User::all();
        $tailors = Tailor::all();
        return view('admin.tailors.orders.create', compact('garmentTypes', 'customers', 'tailors'));
    }

    public function measurements($id)
    {
        return MeasurementField::where('garment_type_id', $id)->get();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'      => ['required', 'exists:users,id'],
            'tailor_id'        => ['required', 'exists:tailors,id'],
            'garment_type_id'  => ['required', 'exists:garment_types,id'],
            'price'            => ['required', 'numeric', 'min:0'],
            'measurements'     => ['required', 'array'],
            'measurements.*'   => ['required'],
        ]);

        TailorOrder::create([
            'customer_id'     => $validated['customer_id'],
            'tailor_id'       => $validated['tailor_id'],
            'garment_type_id' => $validated['garment_type_id'],
            'price'           => $validated['price'],
            'measurements'    => $validated['measurements'],
        ]);

        return redirect()
            ->route('admin.tailor.orders.create')
            ->with('success', 'Order created successfully.');
    }
}
