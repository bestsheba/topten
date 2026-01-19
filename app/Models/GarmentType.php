<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function measurementFields()
    {
        return $this->hasMany(MeasurementField::class);
    }
}
