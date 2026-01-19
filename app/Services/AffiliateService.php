<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\AffiliateEarning;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWithdrawal;
use App\Models\Admin;
use App\Models\Setting;
use App\Mail\WithdrawalRequestMail;
use App\Mail\WithdrawalStatusChangeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    public function ensureUserHasCode(User $user): User
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            throw new \Exception('Affiliate feature is disabled');
        }
        
        if (!$user->affiliate_code) {
            $user->affiliate_code = $this->generateUniqueCode();
            $user->save();
        }
        return $user;
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(str()->random(8));
        } while (User::where('affiliate_code', $code)->exists());
        return $code;
    }

    public function buildAffiliateLink(string $code): string
    {
        return url('/?ref=' . $code);
    }

    public function getOrCreateWallet(User $user): AffiliateWallet
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            throw new \Exception('Affiliate feature is disabled');
        }
        
        return AffiliateWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );
    }

    public function credit(User $user, float $amount, string $reason = null): AffiliateWallet
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            throw new \Exception('Affiliate feature is disabled');
        }
        
        return DB::transaction(function () use ($user, $amount) {
            $wallet = $this->getOrCreateWallet($user);
            $wallet->balance = bcadd($wallet->balance, $amount, 2);
            $wallet->total_earned = bcadd($wallet->total_earned, $amount, 2);
            $wallet->save();
            return $wallet;
        });
    }

    public function requestWithdrawal(User $user, float $amount, ?string $method = null, ?string $accountInfo = null): AffiliateWithdrawal
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            throw new \Exception('Affiliate feature is disabled');
        }
        
        return DB::transaction(function () use ($user, $amount, $method, $accountInfo) {
            $wallet = $this->getOrCreateWallet($user);
            $settings = \App\Models\Setting::first();
            $minAmount = (float) ($settings->affiliate_min_withdrawal_amount ?? 100);

            if ($amount <= 0 || $amount < $minAmount || $amount > (float) $wallet->balance) {
                throw new \InvalidArgumentException("Invalid withdrawal amount. Minimum amount is {$minAmount}");
            }

            $withdrawal = AffiliateWithdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $method,
                'account_info' => $accountInfo,
                'status' => 'pending'
            ]);

            // lock funds by reducing available balance now
            $wallet->balance = bcsub($wallet->balance, $amount, 2);
            $wallet->total_withdrawn = bcadd($wallet->total_withdrawn, $amount, 2);
            $wallet->save();

            // Send email notification to admin
            $this->sendWithdrawalRequestEmail($withdrawal);

            return $withdrawal;
        });
    }

    public function processWithdrawal(AffiliateWithdrawal $withdrawal): AffiliateWithdrawal
    {
        return DB::transaction(function () use ($withdrawal) {
            $wallet = $this->getOrCreateWallet($withdrawal->user);

            // The withdrawal amount was already deducted from balance when requested
            // No additional processing needed here, just return the withdrawal
            return $withdrawal;
        });
    }

    public function rejectWithdrawal(AffiliateWithdrawal $withdrawal): AffiliateWithdrawal
    {
        return DB::transaction(function () use ($withdrawal) {
            $wallet = $this->getOrCreateWallet($withdrawal->user);

            // Refund the amount back to balance
            $wallet->balance = bcadd($wallet->balance, $withdrawal->amount, 2);
            $wallet->total_withdrawn = bcsub($wallet->total_withdrawn, $withdrawal->amount, 2);
            $wallet->save();

            return $withdrawal;
        });
    }

    public function getAffiliateRef()
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            return null;
        }
        
        // Get the currently authenticated user
        $auth_user = Auth::user();

        // Retrieve the affiliate reference from the session
        $ref = session()->get('affiliate_ref');

        // If the authenticated user exists and their affiliate code matches the reference,
        // return null to prevent self-referral
        if ($auth_user && $auth_user->affiliate_code == $ref) {
            return null;
        }

        // Otherwise, return the affiliate reference
        return $ref;
    }

    public function creditForOrder(Order $order): ?AffiliateEarning
    {
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            return null;
        }
        
        if (empty($order->affiliate_ref)) {
            return null;
        }

        $affiliateUser = User::where('affiliate_code', $order->affiliate_ref)->first();
        if (!$affiliateUser) {
            return null;
        }

        return DB::transaction(function () use ($affiliateUser, $order) {
            if (AffiliateEarning::where('order_id', $order->id)->exists()) {
                return AffiliateEarning::where('order_id', $order->id)->first();
            }

            $settings = \App\Models\Setting::first();
            $commissionPercent = (float) ($settings->affiliate_commission_percent ?? 0);
            $amount = round(((float) $order->total) * ($commissionPercent / 100), 2);

            if ($amount <= 0) {
                return null;
            }

            $earning = AffiliateEarning::create([
                'user_id' => $affiliateUser->id,
                'order_id' => $order->id,
                'amount' => $amount,
                'commission_percent' => $commissionPercent,
                'noted_at' => now(),
            ]);

            $this->credit($affiliateUser, $amount, 'Order #' . $order->id . ' delivered');

            return $earning;
        });
    }

    /**
     * Send withdrawal request email to admin
     */
    private function sendWithdrawalRequestEmail(AffiliateWithdrawal $withdrawal): void
    {
        try {
            // Get admin email (you can modify this to get specific admin or all admins)
            $admin = Admin::first();
            if ($admin && $admin->email) {
                Mail::to($admin->email)->send(new WithdrawalRequestMail($withdrawal));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the withdrawal request
            Log::error('Failed to send withdrawal request email: ' . $e->getMessage());
        }
    }

    /**
     * Send withdrawal status change email to user
     */
    public function sendWithdrawalStatusChangeEmail(AffiliateWithdrawal $withdrawal, string $oldStatus, string $newStatus): void
    {
        try {
            if ($withdrawal->user && $withdrawal->user->email) {
                Mail::to($withdrawal->user->email)->send(new WithdrawalStatusChangeMail($withdrawal, $oldStatus, $newStatus));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the status update
            Log::error('Failed to send withdrawal status change email: ' . $e->getMessage());
        }
    }
}
