<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Product;
use Carbon\Carbon;

class FlashDealController extends Controller
{
    /**
     * Get Flash Deal Timer and Related Information
     */
    public function index(Request $request)
    {
        try {
            // Fetch settings
            $settings = Setting::first();

            // Check if flash deal is active
            $isFlashDealActive = $settings->show_flash_deal && 
                                 $settings->flash_deal_timer && 
                                 Carbon::parse($settings->flash_deal_timer)->isFuture();

            // Prepare response data
            $responseData = [
                'success' => true,
                'flash_deal' => [
                    'is_active' => $isFlashDealActive,
                    'timer' => $isFlashDealActive ? $settings->flash_deal_timer : null,
                    'remaining_time' => $isFlashDealActive 
                        ? Carbon::now()->diffInSeconds(Carbon::parse($settings->flash_deal_timer)) 
                        : null
                ]
            ];

            // If flash deal is active, include products
            if ($isFlashDealActive) {
                $flashDealProducts = Product::active()
                    ->offer()
                    ->latest()
                    ->withCount('sold')
                    ->take(10)
                    ->get();

                $responseData['products'] = $flashDealProducts->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'offer_price' => $product->offer_price,
                        'image' => $product->image,
                        'sold_count' => $product->sold_count
                    ];
                });
            }

            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'FLASH_DEAL_ERROR'
            ], 500);
        }
    }
} 