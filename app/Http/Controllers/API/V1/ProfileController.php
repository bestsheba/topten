<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Get user profile details
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('address');

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatarUrl,
                'phone_number' => $user->address?->phone_number,
                'address' => $user->address?->address,
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = Auth::user();

            // Handle avatar upload
            $avatar = $user->avatar;
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($avatar) {
                    Storage::delete($avatar);
                }
                
                // Upload new avatar
                $avatar = $request->file('avatar')->store('avatars', 'public');
            }

            // Update user details
            $user->update([
                'name' => $request->input('name', $user->name),
                'avatar' => $avatar ? 'storage/' . $avatar : $user->avatar,
            ]);

            // Update or create user address
            $user->address()->updateOrCreate(
                [],
                [
                    'name' => $request->input('name', $user->name),
                    'phone_number' => $request->input('phone_number', ''), // Default to empty string if not provided
                    'address' => $request->input('address', ''), // Default to empty string if not provided
                ]
            );

            // Refresh user data
            $user->refresh();
            $user->load('address');

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatarUrl,
                    'phone_number' => $user->address?->phone_number,
                    'address' => $user->address?->address,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to update profile',
                'error_details' => $e->getMessage(),
                'error_code' => 'PROFILE_UPDATE_ERROR'
            ], 500);
        }
    }
} 