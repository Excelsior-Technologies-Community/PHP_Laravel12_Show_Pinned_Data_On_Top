<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'details',
        'image',
        'category_id',
        'is_pinned'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
