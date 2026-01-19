<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPageGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function pages()
    {
        return $this->hasMany(CustomPage::class)->ordered();
    }
}


