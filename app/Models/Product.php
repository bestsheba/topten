<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($product) {
    //         if (empty($product->slug)) {
    //             $slug = Str::slug($product->name);
    //             $originalSlug = $slug;
    //             $counter = 1;
    //             while (Product::where('slug', $slug)->exists()) {
    //                 $slug = $originalSlug . '-' . $counter;
    //                 $counter++;
    //             }

    //             $product->slug = $slug;
    //         }
    //     });
    // }

    public function getPictureUrlAttribute()
    {
        return asset($this->picture);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOffer($query)
    {
        return $query->where('show_special_offer_list', true);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function sold()
    {
        return $this->hasMany(OrderItems::class, 'product_id');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function getFinalPriceAttribute()
    {
        $discount_type = $this->discount_type;
        $discount_amount = $this->discount;

        if ($discount_amount > 0) {
            if ($discount_type == 'percentage') {
                return $this->price - ($this->price * $discount_amount) / 100;
            } else {
                return $this->price - $discount_amount;
            }
        } else {
            return $this->price;
        }
    }

    public function getDiscountAmountAttribute()
    {
        $discount_type = $this->discount_type;
        $discount_amount = $this->discount;

        if ($discount_amount > 0) {
            if ($discount_type == 'percentage') {
                return ($this->price * $discount_amount) / 100;
            } else {
                return $discount_amount;
            }
        } else {
            return 0;
        }
    }
}
