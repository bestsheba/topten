<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\OrderSource;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'status_value',
        'status_b_value',
        'source_label',
        'source_badge',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            $hashed_id = Str::slug(Str::random(2) . '-' . crc32($order->id));
            $order->update([
                'hashed_id' => $hashed_id,
                'affiliate_ref' => session()->get('affiliate_ref')
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    /**
     * Get the Pathao order associated with this order
     */
    public function pathaoOrder()
    {
        return $this->hasOne(PathaoOrder::class, 'order_id');
    }

    /**
     * Get the Steadfast order associated with this order
     */
    public function steadfastOrder()
    {
        return $this->hasOne(SteadfastOrder::class, 'order_id');
    }

    public function getStatusValueAttribute()
    {
        return $this->statusList($this->status);
    }

    // T stand for "Tailwind"
    public function getStatusTValueAttribute()
    {
        //
    }

    // B stand for "Bootstrap"
    public function getStatusBValueAttribute()
    {
        $value = $this->statusList($this->status);
        return $this->BHtmlStatus($this->status, $value);
    }

    public static function statusList($status_code = '', $return_array = false, $status_value = '')
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Confirmed',
            3 => 'Packaging',
            4 => 'Out for delivery',
            5 => 'Delivered',
            6 => 'Canceled',
            7 => 'Returned',
            8 => 'Failed to delivery',
        ];

        if ($return_array) {
            return $statuses;
        }

        if (!empty($status_value)) {
            // Return the key of the given status value
            $status_key = array_search($status_value, $statuses);
            return $status_key !== false ? $status_key : '';
        }

        if (array_key_exists($status_code, $statuses)) {
            return $statuses[$status_code];
        }

        return 'Unknown Status';
    }

    public function BHtmlStatus($status_code, $value)
    {
        $html_statuses = [
            1 => '<div class="badge badge-info">' . $value . '</div>',
            2 => '<div class="badge badge-primary">' . $value . '</div>',
            3 => '<div class="badge badge-secondary">' . $value . '</div>',
            4 => '<div class="badge badge-info">' . $value . '</div>',
            5 => '<div class="badge badge-success">' . $value . '</div>',
            6 => '<div class="badge badge-danger">' . $value . '</div>',
            7 => '<div class="badge badge-dark">' . $value . '</div>',
            8 => '<div class="badge badge-warning">' . $value . '</div>',
        ];


        if (array_key_exists($status_code, $html_statuses)) {
            return $html_statuses[$status_code];
        }

        return 'unknown';
    }

    public static function BStatusColor($status_code)
    {
        $html_statuses = [
            1 => 'info',
            2 => 'primary',
            3 => 'secondary',
            4 => 'info',
            5 => 'success',
            6 => 'danger',
            7 => 'dark',
            8 => 'warning',
        ];

        if (array_key_exists($status_code, $html_statuses)) {
            return $html_statuses[$status_code];
        }

        return 'unknown';
    }

    public static function sourceList($source = '', $return_array = false)
    {
        if ($return_array) {
            return OrderSource::all();
        }

        if (!empty($source)) {
            try {
                $enum = OrderSource::tryFrom($source);
                return $enum ? $enum->label() : 'Unknown Source';
            } catch (\Exception $e) {
                return 'Unknown Source';
            }
        }

        return 'Unknown Source';
    }

    public function getSourceLabelAttribute()
    {
        try {
            $enum = OrderSource::tryFrom($this->source);
            return $enum ? $enum->label() : 'Unknown Source';
        } catch (\Exception $e) {
            return 'Unknown Source';
        }
    }

    public function getSourceBadgeAttribute()
    {
        try {
            $enum = OrderSource::tryFrom($this->source);
            return $enum ? $enum->badge() : '<span class="badge badge-dark">Unknown</span>';
        } catch (\Exception $e) {
            return '<span class="badge badge-dark">Unknown</span>';
        }
    }
}
