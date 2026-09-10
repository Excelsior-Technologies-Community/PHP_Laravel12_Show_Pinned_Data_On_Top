<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'details',
        'image',
        'category_id',
        'is_pinned',
        'pin_priority',
        'pin_start_at',
        'pin_end_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
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