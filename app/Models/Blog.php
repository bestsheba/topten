<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getThumbnailUrlAttribute()
    {
        return asset($this->thumbnail);
    }

    public function getPublishDateAttribute()
    {
        return date('F j, Y', strtotime($this->created_at));
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $value_slug = Str::slug($value);
        $is_exists = Blog::whereSlug($value_slug)->where('id', '!=', $this->id)->exists();

        if ($is_exists) {
            $this->attributes['slug'] = $value_slug . '-' . time() . '-' . uniqid();
        } else {
            $this->attributes['slug'] = $value_slug;
        }
    }
}
