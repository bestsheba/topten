<?php

use App\Http\Middleware\AffiliateTrackerMiddleware;
use App\Http\Middleware\CheckAffiliateEnabled;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureOtpVerified;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'otp.verified' => EnsureOtpVerified::class,
            'affiliate.enabled' => CheckAffiliateEnabled::class
        ]);
        $middleware->appendToGroup('web', [
            AffiliateTrackerMiddleware::class
        ]);
        $middleware->validateCsrfTokens(except: [
            'payment/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });
    })->create();
