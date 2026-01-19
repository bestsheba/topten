<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Setting;

class PaymentMethodController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Fetch settings from the database
            $settings = Setting::first();

            // Define payment methods based on the checkout view
            $paymentMethods = [
                [
                    'name' => 'Cash on Delivery',
                    'type' => 'offline',
                    'description' => 'Pay when you receive your order'
                ],
                [
                    'name' => 'bKash',
                    'type' => 'online',
                    'description' => 'Mobile banking payment method',
                    'number' => $settings ? $settings->bkash_number : null,
                    'note' => $settings ? $settings->bkash_number_note : null
                ],
                [
                    'name' => 'Nagad',
                    'type' => 'online', 
                    'description' => 'Mobile banking payment method',
                    'number' => $settings ? $settings->nagad_number : null,
                    'note' => $settings ? $settings->nagad_number_note : null
                ],
                [
                    'name' => 'Rocket',
                    'type' => 'online',
                    'description' => 'Mobile banking payment method', 
                    'number' => $settings ? $settings->rocket_number : null,
                    'note' => $settings ? $settings->rocket_number_note : null
                ],
                [
                    'name' => 'Bank Account',
                    'type' => 'online',
                    'description' => 'Bank transfer payment method',
                    'number' => $settings ? $settings->bank_account_number : null,
                    'note' => $settings ? $settings->bank_account_number_note : null
                ]
            ];

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
