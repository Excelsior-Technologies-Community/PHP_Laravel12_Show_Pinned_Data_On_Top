<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Tag;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'original_price',
        'discount_price',
        'details',
        'image',
        'category_id',
        'label',
        'brand',
        'tags',
        'specifications',
        'video_url',
        'stock',
        'low_stock_threshold',
        'views',
        'is_pinned',
        'pin_priority',
        'pin_start_at',
        'pin_end_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'original_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'tags' => 'array',
        'specifications' => 'array',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'views' => 'integer',
        'pin_priority' => 'integer',
        'price' => 'decimal:2',
        'pin_start_at' => 'datetime',
        'pin_end_at' => 'datetime',
    ];

    /**
     * Product belongs to category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function tagsRelation()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getSellingPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check whether scheduled pinning is currently active.
     */
    public function isPinCurrentlyActive()
    {
        if (!$this->is_pinned) {
            return false;
        }

        $now = Carbon::now();

        if (
            $this->pin_start_at &&
            $now->lt($this->pin_start_at)
        ) {
            return false;
        }

        if (
            $this->pin_end_at &&
            $now->gt($this->pin_end_at)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get readable pin status.
     */
    public function getPinStatusAttribute()
    {
        if ($this->isPinCurrentlyActive()) {
            return 'Active';
        }

        if (
            $this->is_pinned &&
            $this->pin_start_at &&
            $this->pin_start_at->gt(now())
        ) {
            return 'Scheduled';
        }

        if (
            $this->is_pinned &&
            $this->pin_end_at &&
            $this->pin_end_at->lt(now())
        ) {
            return 'Expired';
        }

        if ($this->is_pinned) {
            return 'Pinned';
        }

        return 'Unpinned';
    }
}