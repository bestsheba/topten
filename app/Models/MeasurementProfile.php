<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementProfile extends Model
{
    protected $fillable = [
        'customer_id',
        'garment_type_id',
        'title',
        'note'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function garmentType()
    {
        return $this->belongsTo(GarmentType::class);
    }

    public function values()
    {
        return $this->hasMany(MeasurementValue::class);
    }
}
