<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use App\Models\MeasurementField;
use App\Models\Tailor;
use App\Models\TailorOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class TailorOrderController extends Controller
{
    public function generateInvoice(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
        ]);

        $orders = TailorOrder::with(['customer', 'garmentType'])
            ->whereIn('id', $request->order_ids)
            ->get();

        $totalAmount = $orders->sum('price');

        $pdf = Pdf::loadView(
            'admin.tailors.orders.invoice',
            compact('orders', 'totalAmount')
        );

        return $pdf->download('invoice-' . now()->format('YmdHis') . '.pdf');
    }
    public function payDue(Request $request)
    {
        $order = TailorOrder::findOrFail($request->order_id);

        $order->paid_amount += $request->pay_amount;

        if ($order->paid_amount >= $order->price) {
            $order->status = 'paid';
        } elseif ($order->paid_amount > 0) {
            $order->status = 'partial';
        } else {
            $order->status = 'due';
        }

        $order->save();

        return response()->json(['success' => true]);
    }

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
            'cash'   => ['nullable', 'numeric', 'min:0'],
            'measurements'     => ['nullable', 'array'],
            'measurements.*'   => ['nullable'],
        ]);

        // 2️⃣ Normalize values
        $price = $validated['price'];
        $collected = $validated['cash'] ?? 0;

        // Prevent over-payment
        if ($collected > $price) {
            $collected = $price;
        }

        // 3️⃣ Determine payment status
        if ($collected == 0) {
            $status = 'due';
        } elseif ($collected < $price) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        // 4️⃣ Create order
        TailorOrder::create([
            'customer_id'     => $validated['customer_id'],
            'tailor_id'       => $validated['tailor_id'],
            'garment_type_id' => $validated['garment_type_id'],
            'price'           => $price,
            'paid_amount'  => $collected,
            'payment_status'  => $status,
            'measurements'    => $validated['measurements'],
        ]);

        // 5️⃣ Redirect
        return redirect()
            ->route('admin.tailor.orders.create')
            ->with('success', 'Order created successfully.');
    }
}
