<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'number',
        'category_id',
        'name',
        'price',
        'quantity',
        'address',
        'postcode',
    ];

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
