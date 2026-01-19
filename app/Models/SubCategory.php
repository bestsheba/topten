<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $value_slug = Str::slug($value);
        $is_exists = SubCategory::whereSlug($value_slug)->where('id', '!=', $this->id)->exists();

        if ($is_exists) {
            $this->attributes['slug'] = $value_slug . '-' . time() . '-' . uniqid();
        } else {
            $this->attributes['slug'] = $value_slug;
        }
    }

    public function getPictureUrlAttribute()
    {
        return asset($this->picture);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
