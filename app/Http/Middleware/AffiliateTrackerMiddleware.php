<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class AffiliateTrackerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if affiliate feature is enabled
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            return $next($request);
        }
        $ref = $request->query('ref') ?: $this->extractRefFromReferer($request->server('HTTP_REFERER'));

        // 2. If ref exists, store it in session (or cookie)
        if ($ref) {
            $request->session()->put('affiliate_ref', $ref);
        }

        return $next($request);
    }

    protected function extractRefFromReferer(?string $referer): ?string
    {
        if (!$referer) return null;

        $parsedUrl = parse_url($referer);
        if (!isset($parsedUrl['query'])) return null;

        parse_str($parsedUrl['query'], $queryParams);
        return $queryParams['ref'] ?? null;
    }
}
