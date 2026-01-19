<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOtp extends Model
{
    protected $fillable = [
        'email', 
        'otp', 
        'expires_at', 
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    public static function generateOtp(string $email): self
    {
        // Delete any existing OTPs for this email
        self::where('email', $email)->delete();

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(15),
            'is_used' => false
        ]);
    }

    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    public function markAsUsed(): bool
    {
        return $this->update(['is_used' => true]);
    }
}
