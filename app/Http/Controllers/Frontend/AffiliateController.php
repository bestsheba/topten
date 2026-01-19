<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Order;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AffiliateController extends Controller
{
    public function __construct(private AffiliateService $affiliateService) {}

    public function showRegister()
    {
        $settings = Setting::first();
        abort_if(!$settings || !$settings->affiliate_feature_enabled, 404);
        
        return view('frontend.pages.affiliate.register');
    }

    public function register(Request $request)
    {
        $settings = Setting::first();
        abort_if(!$settings || !$settings->affiliate_feature_enabled, 404);
        
        if (Auth::check()) {
            $request->validate([
                'agree' => 'accepted',
            ]);
            $user = $request->user();
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Password::defaults()],
                'agree' => 'accepted',
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            Auth::login($user);
        }

        $user->is_affiliate = true;
        $this->affiliateService->ensureUserHasCode($user);

        flashMessage('success', 'Affiliate activated');
        return redirect()->route('user.dashboard');
    }

    public function wallet(Request $request)
    {
        $settings = Setting::first();
        abort_if(!$settings || !$settings->affiliate_feature_enabled, 404);
        
        $user = $request->user();
        abort_if(!$user?->is_affiliate, 403);

        $wallet = $this->affiliateService->getOrCreateWallet($user);
        $withdrawals = $user->hasMany(\App\Models\AffiliateWithdrawal::class)->latest()->paginate(10);
        $settings = \App\Models\Setting::first();

        return view('frontend.pages.affiliate.wallet', compact('wallet', 'withdrawals', 'user', 'settings'));
    }

    public function requestWithdrawal(Request $request)
    {
        $settings = \App\Models\Setting::first();
        abort_if(!$settings || !$settings->affiliate_feature_enabled, 404);
        
        $minAmount = (float) ($settings->affiliate_min_withdrawal_amount ?? 100);
        
        $validated = $request->validate([
            'amount' => ['required', 'numeric', "min:{$minAmount}"],
            'method' => ['required', 'string', 'max:100'],
            'account_info' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        abort_if(!$user?->is_affiliate, 403);

        try {
            $this->affiliateService->requestWithdrawal(
                $user,
                (float) $request->amount,
                $request->method,
                $request->account_info
            );
            flashMessage('success', 'Withdrawal request submitted');
        } catch (\InvalidArgumentException $e) {
            return Redirect::back()->withErrors(['withdrawal' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors(['withdrawal' => 'Something went wrong'])->withInput();
        }

        return Redirect::back();
    }

    public function earnings(Request $request)
    {
        $settings = Setting::first();
        abort_if(!$settings || !$settings->affiliate_feature_enabled, 404);
        
        $user = $request->user();
        abort_if(!$user?->is_affiliate, 403);

        $this->affiliateService->ensureUserHasCode($user);

        $settings = Setting::first();
        $commissionPercent = (float) ($settings->affiliate_commission_percent ?? 0);

        $ordersQuery = Order::query()
            ->where('affiliate_ref', $user->affiliate_code)
            ->latest();

        $orders = $ordersQuery->paginate(15)->through(function ($order) use ($commissionPercent) {
            $order->affiliate_commission = round(((float) $order->total) * ($commissionPercent / 100), 2);
            return $order;
        });

        $summary = [
            'total_orders' => (clone $ordersQuery)->count(),
            'total_sales' => (clone $ordersQuery)->sum('total'),
        ];
        $summary['total_commission'] = round(((float) $summary['total_sales']) * ($commissionPercent / 100), 2);

        return view('frontend.pages.affiliate.earnings', [
            'user' => $user,
            'orders' => $orders,
            'commissionPercent' => $commissionPercent,
            'summary' => $summary,
        ]);
    }
}


