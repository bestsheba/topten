<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Teasure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedHost = 'suzayet.com';
        $currentHost = $request->getHost();

        if ($currentHost !== $allowedHost) {
            return response('', 200);
        }

        return $next($request);
    }
}
