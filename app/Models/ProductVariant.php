<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'value', 'price_adjustment', 'stock'];

    protected $casts = ['price_adjustment' => 'decimal:2', 'stock' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}