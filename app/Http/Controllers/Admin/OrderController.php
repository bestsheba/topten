<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\IncompleteOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusNotification;
use App\Services\AffiliateService;

class OrderController extends Controller
{
    /**
     * Display POS terminal page
     */
    public function pos()
    {
        if (!userCan('view orders')) {
            abort(403);
        }

        return view('admin.pages.pos');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!userCan('view orders')) {
            abort(403);
        }
        // Basic statistics for the orders overview cards
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');

        $stats = [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'today_orders' => Order::whereDate('created_at', now()->toDateString())->count(),
            'this_month_orders' => Order::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'pending' => Order::where('status', 1)->count(),
            'delivered' => Order::where('status', 5)->count(),
            'failed' => Order::where('status', 8)->count(),
            'average_order_value' => $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0,
        ];

        // Additional useful stats
        $stats['completed'] = $stats['delivered'];
        $stats['courier_sent'] = Order::whereNotNull('courier')->count();

        $orders = Order::with('user')->latest()
            ->when($request->filled('keyword'), function ($q) use ($request) {

                $keyword = $request->keyword;

                // Check if the keyword matches a status first
                $status = Order::statusList('', false, $keyword);

                $q->where(function ($query) use ($keyword, $status) {
                    $query->where('hashed_id', $keyword)
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->where('name', 'LIKE', "%{$keyword}%")
                                ->orWhere('email', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhere('status', $status)
                        ->orWhere('customer_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('customer_phone_number', 'LIKE', "%{$keyword}%");
                });
            })
            ->when($request->filled('filter'), function ($q) use ($request) {
                $filter = $request->filter;

                switch ($filter) {
                    case 'pending':
                        // status 1 is treated as pending in stats
                        $q->where('status', 1);
                        break;
                    case 'completed':
                        // delivered/completed status
                        $q->where('status', 5);
                        break;
                    case 'failed':
                        $q->where('status', 8);
                        break;
                    case 'today_orders':
                        $q->whereDate('created_at', now()->toDateString());
                        break;
                    case 'this_month_orders':
                        $q->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                        break;
                    case 'courier_sent':
                        $q->whereNotNull('courier');
                        break;
                    // For filters that are overview stats but not a specific query (like total_orders,
                    // total_revenue, average_order_value), we don't modify the query and show all orders.
                    default:
                        break;
                }
            })
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                $status = $request->payment_status;
                if ($status === 'due') {
                    $q->where(function ($subQ) {
                        $subQ->where('payment_status', 'due')
                            ->orWhereNull('payment_status');
                    });
                } else {
                    $q->where('payment_status', $status);
                }
            })
            ->paginate(20);

        return view('admin.pages.order.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if (!userCan('view orders')) {
            abort(403);
        }
        $order->load('userInfo', 'user', 'items.product');

        return view('admin.pages.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }
        $statuses = Order::statusList('', true);

        return view('admin.pages.order.edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, AffiliateService $affiliateService)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }
        $request->validate([
            'status' => 'required'
        ]);

        $previousStatus = $order->status;
        $order->update([
            'status' => $request->status
        ]);

        if ((int)$request->status === 5 && (int)$previousStatus !== 5) {
            try {
                $affiliateService->creditForOrder($order);
            } catch (\Throwable $e) {
                // swallow; do not block order update on affiliate credit failures
            }
        }

        if ($order->user) {
            try {
                $title = 'Your Order #' . $order->hashed_id . ' Marked As ' . ucfirst(Order::statusList($order->status));
                Notification::send($order->user, new OrderStatusNotification($order, $title));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        flashMessage('success', 'Order updated');
        return redirect()->route('admin.order.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }
        $order->userInfo?->delete();
        $order->items()->delete();

        $order->delete();

        flashMessage('success', 'Order deleted');
        return redirect()->back();
    }

    public function download(Order $order)
    {
        if (!userCan('download invoice')) {
            abort(403);
        }
        $order->load('userInfo', 'items.product', 'user');

        $setting = Setting::first();

        $imagePath = public_path($setting->website_logo);
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // return view('invoice', compact('order', 'base64', 'setting'));
        $pdf = PDF::loadView('invoice', compact('order', 'base64', 'setting'))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('invoice.pdf');
    }

    /**
     * Delete multiple orders at once
     */
    public function bulkDelete(Request $request)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }

        $orderIds = $request->input('order_ids', []);

        // Delete related data first
        Order::whereIn('id', $orderIds)->each(function ($order) {
            $order->userInfo?->delete();
            $order->items()->delete();
        });

        // Then delete the orders
        Order::whereIn('id', $orderIds)->delete();

        flashMessage('success', count($orderIds) . ' orders have been deleted');
        return redirect()->back();
    }

    /**
     * Update status of multiple orders at once
     */
    public function bulkStatusUpdate(Request $request, AffiliateService $affiliateService)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }

        $orderIds = $request->input('order_ids', []);
        $newStatus = $request->input('status');

        $orders = Order::whereIn('id', $orderIds)->get();

        foreach ($orders as $order) {
            $previousStatus = $order->status;

            $order->update([
                'status' => $newStatus
            ]);

            // Handle affiliate credits for completed orders
            if ((int)$newStatus === 5 && (int)$previousStatus !== 5) {
                try {
                    $affiliateService->creditForOrder($order);
                } catch (\Throwable $e) {
                    // swallow; do not block order update on affiliate credit failures
                }
            }

            // Send notification to user
            if ($order->user) {
                try {
                    $title = 'Your Order #' . $order->hashed_id . ' Marked As ' . ucfirst(Order::statusList($newStatus));
                    Notification::send($order->user, new OrderStatusNotification($order, $title));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        flashMessage('success', count($orderIds) . ' orders have been updated');
        return redirect()->back();
    }

    /**
     * Store payment for an order
     */
    public function storePayment(Request $request)
    {
        if (!userCan('manage orders')) {
            abort(403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'nullable',
            'transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'created_at' => 'nullable|date',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Calculate current paid amount and due amount
        $currentPaidAmount = $order->paid_amount ?? 0;
        $dueAmount = $order->total - $currentPaidAmount;

        // Validate that payment doesn't exceed due amount
        if ($request->amount > $dueAmount) {
            flashMessage('error', 'Payment amount cannot exceed due amount of ' . number_format($dueAmount, 2));
            return redirect()->back();
        }

        // Calculate new paid amount
        $newPaidAmount = $currentPaidAmount + $request->amount;

        // Determine payment status
        $paymentStatus = 'partial';
        if ($newPaidAmount >= $order->total) {
            $paymentStatus = 'paid';
            $newPaidAmount = $order->total; // Cap at total amount
        } elseif ($newPaidAmount <= 0) {
            $paymentStatus = 'due';
        }

        $order->update([
            'payment_transaction_id' => $request->transaction_id ?? $order->payment_transaction_id,
            'payment_method' => $request->payment_method,
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
        ]);

        flashMessage('success', 'Payment added successfully. Status: ' . ucfirst($paymentStatus));
        return redirect()->back();
    }
}
