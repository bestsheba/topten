<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Model\OrderDetail;
use Illuminate\Http\Request;
use App\Model\ShippingAddress;
use App\Models\IncompleteOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrderItems;
use Brian2694\Toastr\Facades\Toastr;

class IncompleteOrderController extends Controller
{
    public function index()
    {
        if (!userCan('manage incomplete orders')) {
            abort(403);
        }

        $orders = IncompleteOrder::where(function ($query) {
            $query->whereNotNull('name')
                ->orWhereNotNull('phone_number')
                ->orWhereNotNull('address');
        })->latest()->paginate(20);

        $count = IncompleteOrder::where(function ($query) {
            $query->whereNotNull('name')
                ->orWhereNotNull('phone_number')
                ->orWhereNotNull('address');
        })->count();

        return view('admin.pages.order.incomplete-order', compact('orders', 'count'));
    }

    public function delete($order)
    {
        if (!userCan('manage incomplete orders')) {
            abort(403);
        }

        $order = IncompleteOrder::findOrFail($order);
        $order->delete();

        return redirect()->route('admin.order.incomplete')->with('success', 'Data deleted successfully!');
    }

    public function placeOrderForm($incomplete_order)
    {
        if (!userCan('manage incomplete orders')) {
            abort(403);
        }

        $inc_order = IncompleteOrder::findOrFail($incomplete_order);

        return view('admin.pages.order.place-incomplete-order', compact('inc_order'));
    }

    public function placeOrder($incomplete_order, Request $request)
    {
        if (!userCan('manage incomplete orders')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        $incomplete_order = IncompleteOrder::where('id', $incomplete_order)->first();

        try {
            DB::beginTransaction();

            $name = $request->name;
            $phone = $request->phone_number;
            $address = $request->address;
            $delivery_charge = $request->delivery_charge;

            $order = Order::create([
                'subtotal' => 0,
                'total' => 0,
                'status' => 1,
                'payment_method' => 'cash_on_delivery',
                'customer_name' => $name,
                'customer_phone_number' =>  $phone,
                'customer_address' => $address,
            ]);

            $total = 0;

            foreach ($incomplete_order->products as $key => $product) {

                $total += $product['product']['price'] * $product['quantity'];

                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product']['id'],
                    // 'size_id' => '',
                    // 'color_id' => '',
                    // 'size_name' => '',
                    // 'size_code' => '',
                    // 'color_name' => '',
                    // 'color_code' => '',
                    // 'color_hex_code' => '',
                    // 'additional_price' => '',
                    'quantity' => $product['quantity'],
                    'price' => $product['product']['price'],
                    'total' => $product['product']['price'] * $product['quantity'],
                ]);
            }

            $order->update([
                'total' => $total + $delivery_charge,
                'subtotal' => $total,
            ]);

            $incomplete_order->delete();

            DB::commit();
            return redirect()->route('admin.order.incomplete')->with('success', 'Order created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
