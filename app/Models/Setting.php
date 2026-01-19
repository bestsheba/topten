<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getWebsiteLogoPathAttribute()
    {
        return asset($this->website_logo);
    }

    public function getWebsiteFaviconPathAttribute()
    {
        return asset($this->website_favicon);
    }

    public function getShowFlashDealAttribute()
    {
        if ($this->flash_deal_timer >= now()) {
            return true;
        }

        return false;
    }
}
