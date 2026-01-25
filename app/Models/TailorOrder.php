<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TailorOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'tailor_id',
        'garment_type_id',
        'price',
        'measurements',
        'collected_cash',
        'paid_amount',
        'status',
    ];

    protected $casts = [
        'measurements' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }

    public function garmentType()
    {
        return $this->belongsTo(GarmentType::class);
    }
}
