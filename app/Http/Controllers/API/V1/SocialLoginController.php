<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'provider' => 'required|string|in:google,facebook,github,twitter,linkedin-openid',
            'access_token' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'provider_id' => 'required|string',
            'avatar' => 'nullable'
        ]);

        if ($validated->fails()) {
            $responseArr['message'] = $validated->errors();;
            return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
        }

        try {

            $validated = $validated->validated();

            // SECURITY: Verify access token with provider
            $tokenData = $this->verifyAccessTokenWithProvider(
                $validated['provider'],
                $validated['access_token']
            );

            if (!$tokenData) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid access token'
                ], 401);
            }

            // SECURITY: Verify email matches token data
            if ($tokenData['email'] !== $validated['email']) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Email mismatch with token data'
                ], 401);
            }

            // SECURITY: Verify provider_id matches token data
            if ($tokenData['id'] !== $validated['provider_id']) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Provider ID mismatch with token data'
                ], 401);
            }

            // Find existing user by email
            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $request->provider_id,
                    'social_provider' => 'google',
                    'password' => Hash::make(str()->random(16)), // Random password for social login
                    'avatar' => $request->avatar
                ]
            );

            // Send OTP for verification
            $otp = UserOtp::generateOtp($user->email);
            $googleAuthController = new GoogleAuthController();
            $googleAuthController->sendOtpEmail($user->email, $otp->otp);

            // Create auth token
            $token = $user->createToken('social-auth-token')->plainTextToken;

            return response()->json([
                'status' => 1,
                'message' => 'Social login successful',
                'token' => $token,
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Social login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify social provider token (optional for extra security)
     */
    public function verifySocialToken(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'provider' => 'required|string|in:google,facebook',
                'access_token' => 'required|string',
            ]);

            $isValid = false;
            $userData = null;

            switch ($validated['provider']) {
                case 'google':
                    $isValid = $this->verifyGoogleToken($validated['access_token']);
                    break;
                case 'facebook':
                    $isValid = $this->verifyFacebookToken($validated['access_token']);
                    break;
            }

            return response()->json([
                'status' => $isValid ? 1 : 0,
                'message' => $isValid ? 'Token is valid' : 'Token is invalid',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token verification failed'
            ], 500);
        }
    }

    /**
     * Verify access token with social provider and get user data
     */
    private function verifyAccessTokenWithProvider($provider, $accessToken)
    {
        try {
            switch ($provider) {
                case 'google':
                    return $this->verifyGoogleToken($accessToken);
                case 'facebook':
                    return $this->verifyFacebookToken($accessToken);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Token verification failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verify Google access token and return user data
     */
    private function verifyGoogleToken($accessToken)
    {
        try {
            $response = Http::get('https://www.googleapis.com/oauth2/v1/userinfo', [
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                $userData = $response->json();
                return [
                    'id' => $userData['id'],
                    'email' => $userData['email'],
                    'name' => $userData['name'],
                ];
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify Facebook access token and return user data
     */
    private function verifyFacebookToken($accessToken)
    {
        try {
            $response = Http::get('https://graph.facebook.com/me', [
                'access_token' => $accessToken,
                'fields' => 'id,name,email,picture.width(200)'
            ]);

            if ($response->successful()) {
                $userData = $response->json();
                return [
                    'id' => $userData['id'],
                    'email' => $userData['email'] ?? null,
                    'name' => $userData['name'],
                ];
            }
            return false;
        } catch (\Exception $e) {
            return false;
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
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP']
            ]);
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Mark user as OTP verified
        $user->update([
            'otp_verified_at' => now()
        ]);

        return response()->json([
            'message' => 'OTP verified successfully',
            'user' => $user
        ]);
    }

    public function resendOtp(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'user' => ['User not found']
            ]);
        }

        // Delete any existing OTPs for this email
        UserOtp::where('email', $user->email)->delete();

        // Generate and send new OTP
        $otp = UserOtp::generateOtp($user->email);

        $googleAuthController = new GoogleAuthController();
        $googleAuthController->sendOtpEmail($user->email, $otp->otp);

        return response()->json([
            'message' => 'New OTP sent successfully'
        ]);
    }
}
