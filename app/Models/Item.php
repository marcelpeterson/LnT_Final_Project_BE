<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'quantity',
        'photo',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
