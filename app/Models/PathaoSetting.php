<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathaoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'environment',
        'base_url',
        'client_id',
        'client_secret',
        'username',
        'password',
        'grant_type',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'store_id',
        'store_name',
        'store_address',
        'is_active'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active Pathao settings
     */
    public static function getActiveSettings()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired()
    {
        if (!$this->token_expires_at) {
            return true;
        }

        return now()->isAfter($this->token_expires_at);
    }
}