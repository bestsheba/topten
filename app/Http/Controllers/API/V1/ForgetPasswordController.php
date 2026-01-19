<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ForgetPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgetPasswordController extends Controller
{
    /**
     * Handle forget password request
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();

            // Generate a reset token
            $token = Str::random(64);

            // Delete any existing reset tokens for this user
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Store the reset token
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // Construct reset link
            $resetLink = url("/reset-password?token={$token}");

            // Send reset password email
            Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email',
                'reset_link' => $resetLink // Only for testing, remove in production
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to process password reset request',
                'error_details' => $e->getMessage(),
                'error_code' => 'FORGET_PASSWORD_ERROR'
            ], 500);
        }
    }
} 