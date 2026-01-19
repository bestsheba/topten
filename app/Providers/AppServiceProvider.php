<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\Category;
use App\Models\CustomPage;
use App\Models\CustomPageGroup;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        RedirectIfAuthenticated::redirectUsing(function ($request) {

            if ($request->routeIs('admin.*')) {
                return route('admin.dashboard');
            }

            return $request->expectsJson() ? null : url('/');
        });

        Authenticate::redirectUsing(function ($request) {

            if ($request->routeIs('admin.*')) {
                return route('admin.login');
            }

            return $request->expectsJson() ? null : url('/login');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $view->with('settings', Setting::first());

            $view->with('header_categories', Category::with([
                'subCategories' => function ($query) {
                    $query->active();
                }
            ])->active()->ordered()->get());

            $view->with('custom_pages', CustomPage::ordered()->get());
            $view->with('custom_page_groups', CustomPageGroup::ordered()->with('pages')->get());
            $view->with('notice', Notice::first());
        });

        // Load Google settings from database and merge with config
        try {
            $settings = Setting::first();
            if ($settings && $settings->google_login_enabled) {
                config([
                    'services.google.client_id' => $settings->google_client_id ?: config('services.google.client_id'),
                    'services.google.client_secret' => $settings->google_client_secret ?: config('services.google.client_secret'),
                ]);
            }
        } catch (\Exception $e) {
            // If database is not ready or settings table doesn't exist, use default config
        }

        // set steadfast courier config from database
        try {
            $steadfastSettings = \App\Models\SteadfastSetting::first();
            if ($steadfastSettings) {
                config([
                    'steadfast-courier.base_url' => $steadfastSettings->base_url,
                    'steadfast-courier.api_key' => $steadfastSettings->base_url,
                    'steadfast-courier.secret_key' => $steadfastSettings->api_key,
                ]);
            }
        } catch (\Exception $e) {
            // If database is not ready or settings table doesn't exist, use default config
        }

        // Load Stripe settings from database and merge with config
        try {
            $settings = Setting::first();
            if ($settings && $settings->stripe_enabled) {
                config([
                    'services.stripe.public_key' => $settings->stripe_public_key ?: config('services.stripe.public_key'),
                    'services.stripe.secret_key' => $settings->stripe_secret_key ?: config('services.stripe.secret_key'),
                    'services.stripe.mode' => $settings->stripe_mode ?: config('services.stripe.mode'),
                ]);
            }
        } catch (\Exception $e) {
            // If database is not ready or settings table doesn't exist, use default config
        }
    }
}
