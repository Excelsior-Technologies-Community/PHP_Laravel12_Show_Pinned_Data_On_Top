<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'name', 'rating', 'comment', 'approved'];

    protected $casts = ['approved' => 'boolean', 'rating' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}