<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\OrderPaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        DB::beginTransaction();
        try {
            $order = Order::where('id', $request->value_a)->first();

            if ($order) {
                $order->update([
                    'payment_status' => OrderPaymentStatus::PAID->value,
                    'transaction_id' => $request->input('tran_id'),
                ]);

                DB::commit();
                return view('frontend.pages.payment.success', compact('order'));
            }

            throw new \Exception('Order not found or invalid status');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment success processing failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Unable to process payment confirmation. Please contact support.');
        }
    }

    public function failed(Request $request)
    {
        $message = $request->input('message', 'Payment failed. Please try again.');

        // If there's a transaction ID, update the order status
        if ($request->has('tran_id')) {
            try {
                $order = Order::where('id', $request->tran_id)->first();
                if ($order) {
                    $order->update([
                        'status' => 4, // Failed
                        'payment_status' => 'failed',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update order status: ' . $e->getMessage());
            }
        }

        return view('frontend.pages.payment.failed', compact('message'));
    }

    public function cancel(Request $request)
    {
        $message = 'Payment was canceled.';

        // If there's a transaction ID, update the order status
        if ($request->has('tran_id')) {
            try {
                $order = Order::where('id', $request->value_a)->first();
                if ($order) {
                    $order->delete();
                }
            } catch (\Exception $e) {
                Log::error('Failed to update order status: ' . $e->getMessage());
            }
        }

        return view('frontend.pages.payment.failed', compact('message'));
    }
}
