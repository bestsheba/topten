<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
    protected $stripeKey;
    protected $stripeMode;

    public function __construct()
    {
        $this->stripeKey = config('services.stripe.secret_key');
        $this->stripeMode = config('services.stripe.mode', 'test');

        if ($this->stripeKey) {
            Stripe::setApiKey($this->stripeKey);
        }
    }

    /**
     * Create a Stripe checkout session
     */
    public function createCheckoutSession(Order $order, $items)
    {
        try {
            $currency = strtolower(currencyCode()); // Get app currency (e.g., 'bdt')
            $lineItems = [];

            foreach ($items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $item['name'] ?? 'Product',
                        ],
                        'unit_amount' => intval($item['price'] * 100), // Amount in cents
                    ],
                    'quantity' => $item['quantity'] ?? 1,
                ];
            }

            // Add delivery charge as a separate line item if applicable
            if ($order->delivery_charge > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Delivery Charge',
                        ],
                        'unit_amount' => intval($order->delivery_charge * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'customer_email' => $order->email ?? auth('web')->user()?->email,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ],
            ]);

            return $session;
        } catch (\Exception $e) {
            \Log::error('Stripe session creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieve checkout session details
     */
    public function getSession($sessionId)
    {
        try {
            return Session::retrieve($sessionId);
        } catch (\Exception $e) {
            \Log::error('Stripe session retrieval error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify payment from session
     */
    public function verifyPayment($sessionId)
    {
        try {
            $session = $this->getSession($sessionId);

            if ($session->payment_status === 'paid') {
                return [
                    'success' => true,
                    'session_id' => $session->id,
                    'payment_intent' => $session->payment_intent,
                    'customer_email' => $session->customer_email,
                    'metadata' => $session->metadata,
                ];
            }

            return [
                'success' => false,
                'status' => $session->payment_status,
            ];
        } catch (\Exception $e) {
            \Log::error('Stripe payment verification error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
