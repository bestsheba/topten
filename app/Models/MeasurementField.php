<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementField extends Model
{
     use HasFactory;
    protected $fillable = [
        'garment_type_id',
        'key',
        'label',
        'unit',
        'sort_order'
    ];

    public function garmentType()
    {
        return $this->belongsTo(GarmentType::class);
    }
}
