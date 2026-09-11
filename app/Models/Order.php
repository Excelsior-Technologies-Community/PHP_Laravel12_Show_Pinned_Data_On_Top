<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'order_number', 'customer_name', 'customer_email',
        'customer_phone', 'shipping_address', 'subtotal', 'shipping_amount', 'total',
        'payment_method', 'status',
    ];

    protected $casts = ['subtotal' => 'decimal:2', 'shipping_amount' => 'decimal:2', 'total' => 'decimal:2'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
