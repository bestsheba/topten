<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateWallet extends Model
{
    protected $fillable = [
        'user_id', 'balance', 'total_earned', 'total_withdrawn'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
