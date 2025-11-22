<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'delivery_date',
        'delivery_time',
        'subtotal',
        'tax',
        'delivery_fee',
        'total',
        'status',
        'notes',
        'internal_notes',
        'payment_method',
        'payment_status',
        'payment_id',
        'mercadopago_preference_id',
        'paid_at'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
