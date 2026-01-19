<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferSlider extends Model
{
    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        return asset($this->image);
    }
}
