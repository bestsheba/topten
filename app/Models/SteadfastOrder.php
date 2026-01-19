<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteadfastOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice',
        'consignment_id',
        'tracking_code',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'cod_amount',
        'status',
        'response_data'
    ];

    protected $casts = [
        'cod_amount' => 'decimal:2',
        'response_data' => 'array'
    ];

    /**
     * Get the order that owns the Steadfast order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'badge-info',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            'in_review' => 'badge-warning',
        ];

        $badgeClass = $statuses[$this->status] ?? 'badge-secondary';

        return '<span class="badge ' . $badgeClass . '">' . ($this->status ?? 'unknown') . '</span>';
    }
}
