<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
