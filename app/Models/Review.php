<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'user_id', 
        'star', 
        'comment'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope to get reviews for a specific product
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
