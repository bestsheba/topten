<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\OfferSlider;

class SliderController extends Controller
{
    /**
     * Get all active sliders
     */
    public function index(Request $request)
    {
        try {
            // Determine slider type from request
            $type = $request->input('type', 'all');

            // Prepare response data
            $responseData = [
                'success' => true,
                'sliders' => []
            ];

            // Fetch regular sliders if type is 'all' or 'regular'
            if ($type === 'all' || $type === 'regular') {
                $regularSliders = Slider::orderBy('id', 'asc')
                    ->get()
                    ->map(function($slider) {
                        return [
                            'id' => $slider->id,
                            'title' => $slider->title,
                            'description' => $slider->description,
                            'image' => asset($slider->image),
                            'link' => $slider->link,
                            'type' => 'regular'
                        ];
                    });
                
                $responseData['sliders'] = array_merge(
                    $responseData['sliders'], 
                    $regularSliders->toArray()
                );
            }

            // Fetch offer sliders if type is 'all' or 'offer'
            if ($type === 'all' || $type === 'offer') {
                $offerSliders = OfferSlider::orderBy('id', 'asc')
                    ->get()
                    ->map(function($slider) {
                        return [
                            'id' => $slider->id,
                            'title' => $slider->title,
                            'image' => asset($slider->image),
                            'link' => $slider->link,
                            'type' => 'offer'
                        ];
                    });
                
                $responseData['sliders'] = array_merge(
                    $responseData['sliders'], 
                    $offerSliders->toArray()
                );
            }

            // Sort sliders by order if multiple types are fetched
            if ($type === 'all') {
                usort($responseData['sliders'], function($a, $b) {
                    return $a['id'] <=> $b['id'];
                });
            }

            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'SLIDER_FETCH_ERROR'
            ], 500);
        }
    }
} 