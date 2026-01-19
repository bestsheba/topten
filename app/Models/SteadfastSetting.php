<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteadfastSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_url',
        'api_key',
        'secret_key',
    ];
}
