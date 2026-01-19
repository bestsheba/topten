<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['picture_url'];

    public function getPictureUrlAttribute()
    {
        return asset($this->picture);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc');
    }
      public function activeSubCategories()
    {
        return $this->hasMany(SubCategory::class)->where('is_active', true);
    }
}
