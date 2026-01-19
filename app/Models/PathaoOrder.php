<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathaoOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'merchant_order_id',
        'consignment_id',
        'recipient_name',
        'recipient_phone',
        'recipient_secondary_phone',
        'recipient_address',
        'recipient_city',
        'recipient_zone',
        'recipient_area',
        'delivery_type',
        'item_type',
        'special_instruction',
        'item_quantity',
        'item_weight',
        'item_description',
        'amount_to_collect',
        'delivery_fee',
        'order_status',
        'response_data'
    ];

    protected $casts = [
        'item_weight' => 'decimal:2',
        'amount_to_collect' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'response_data' => 'array'
    ];

    /**
     * Get the order that owns the Pathao order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get delivery type text
     */
    public function getDeliveryTypeTextAttribute()
    {
        return $this->delivery_type == 48 ? 'Normal Delivery' : 'On Demand Delivery';
    }

    /**
     * Get item type text
     */
    public function getItemTypeTextAttribute()
    {
        return $this->item_type == 1 ? 'Document' : 'Parcel';
    }

    /**
     * Get order status badge
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'Pending' => 'badge-info',
            'Confirmed' => 'badge-primary',
            'Picked' => 'badge-secondary',
            'On_the_way' => 'badge-warning',
            'Delivered' => 'badge-success',
            'Cancelled' => 'badge-danger',
            'Returned' => 'badge-dark'
        ];

        $badgeClass = $statuses[$this->order_status] ?? 'badge-secondary';

        return '<span class="badge ' . $badgeClass . '">' . $this->order_status . '</span>';
    }
}
