<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class DeliveryChargeController extends Controller
{
    public function getDeliveryCharges(): JsonResponse
    {
        try {
            $settings = Setting::first();
            
            return response()->json([
                'success' => true,
                'delivery_fee_inside_dhaka' => $settings->delivery_fee ?? 0,
                'delivery_fee_outside_dhaka' => $settings->delivery_fee_outside_of_dhaka ?? 0
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
