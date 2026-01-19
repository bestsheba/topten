<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'social_provider' => 'google',
                    'password' => Hash::make(str()->random(16)), // Random password for social login
                    'avatar' => $googleUser->avatar
                ]
            );

            // Send OTP for verification
            $otp = UserOtp::generateOtp($user->email);
            $this->sendOtpEmail($user->email, $otp->otp);

            Auth::login($user);

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $user = auth()->user();

        $otpRecord = UserOtp::where('email', $user->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return redirect()->back()->with('error', 'Invalid or expired OTP');
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Mark user as OTP verified
        $user->update([
            'otp_verified_at' => now()
        ]);

        return redirect('/')->with('success', 'Authentication successful');
    }

    public function showOtpVerify()
    {
        $user = Auth::user();

        // If already verified, redirect to dashboard
        if ($user->otp_verified_at) {
            return redirect()->route('user.dashboard');
        }

        return view('frontend.pages.auth.otp-verify', compact('user'));
    }

    public function sendOtpEmail(string $email, string $otp)
    {
        Mail::send([], [], function ($message) use ($email, $otp) {
            $message->to($email)
                ->subject('Your One-Time Password (OTP) for Login')
                ->html("
                    <!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                            .container { background-color: #f4f4f4; padding: 20px; border-radius: 10px; }
                            .otp-code { 
                                background-color: #007bff; 
                                color: white; 
                                padding: 10px 20px; 
                                font-size: 24px; 
                                letter-spacing: 5px; 
                                border-radius: 5px; 
                                display: inline-block; 
                                margin: 20px 0;
                            }
                            .footer { margin-top: 20px; font-size: 12px; color: #777; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h2>Login Verification</h2>
                            <p>Hello,</p>
                            <p>You have requested to log in to your account. Please use the One-Time Password (OTP) below to complete your login:</p>
                            <div class='otp-code'>{$otp}</div>
                            <p>This OTP is valid for a limited time. Do not share it with anyone.</p>
                            <p>If you did not request this login, please ignore this email or contact our support.</p>
                        </div>
                    </body>
                    </html>
                    ");
        });
    }

    public function resendOtp(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Delete any existing OTPs for this email
        UserOtp::where('email', $user->email)->delete();

        // Generate and send new OTP
        $otp = UserOtp::generateOtp($user->email);
        $this->sendOtpEmail($user->email, $otp->otp);

        return redirect()->back()->with('success', 'New OTP sent');
    }
}
