<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class StripePaymentController extends Controller
{
    /**
     * Handle successful Stripe payment
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');

            if (!$sessionId) {
                return redirect()->route('index')->with('error', 'Invalid session ID');
            }

            $stripeService = new StripeService();
            $paymentResult = $stripeService->verifyPayment($sessionId);

            if (!$paymentResult['success']) {
                Log::warning('Stripe payment verification failed', $paymentResult);
                return redirect()->route('checkout')->with('error', 'Payment verification failed');
            }

            // Get order from metadata
            $metadata = $paymentResult['metadata'] ?? null;
            if (!$metadata || !isset($metadata->order_id)) {
                return redirect()->route('checkout')->with('error', 'Order not found');
            }

            $order = Order::find($metadata->order_id);
            if (!$order) {
                return redirect()->route('checkout')->with('error', 'Order not found');
            }

            // Log payment result for debugging
            Log::info('Stripe payment result:', [
                'order_id' => $order->id,
                'payment_intent' => $paymentResult['payment_intent'],
                'session_id' => $paymentResult['session_id'],
            ]);

            // Update order status to Confirmed (2)
            $updateResult = $order->update([
                'status' => 2, // Confirmed
                'payment_transaction_id' => $paymentResult['payment_intent'],
            ]);

            Log::info('Order update result:', [
                'order_id' => $order->id,
                'update_result' => $updateResult,
                'order_data' => $order->fresh()->toArray(),
            ]);

            // Clear cart from session
            session()->forget(['cart', 'checkout_coupon', 'incomplete_order_id']);

            return redirect()->route('order.success', $order->id)->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            Log::error('Stripe success handler error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'An error occurred while processing your payment');
        }
    }

    /**
     * Handle cancelled Stripe payment
     */
    public function cancel(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');

            // Delete incomplete order if payment was cancelled
            if ($sessionId) {
                // Find and delete the incomplete order associated with this session if needed
            }

            return redirect()->route('checkout')->with('warning', 'Payment was cancelled. Please try again.');
        } catch (\Exception $e) {
            Log::error('Stripe cancel handler error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'An error occurred');
        }
    }
}
