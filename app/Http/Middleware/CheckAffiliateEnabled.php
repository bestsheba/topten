<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckAffiliateEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Setting::first();
        
        // If affiliate feature is disabled, return 404
        if (!$settings || !$settings->affiliate_feature_enabled) {
            abort(404, 'Affiliate feature is currently disabled');
        }

        return $next($request);
    }
}
