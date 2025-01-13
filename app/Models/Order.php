<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [

        'order_number',
        'user_id',
        'payment_method',
        'address',
        'city',
        'country',
        'total',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->uuid = Str::uuid();
        });
    }

    protected static function generateOrderNumber()
    {
        $lastOrder = self::orderBy('id', 'desc')->first();
        $orderNumber = $lastOrder ? 'ORD-'.str_pad($lastOrder->id + 1, 6, '0', STR_PAD_LEFT) : 'ORD-000001';

        return $orderNumber;
    }
}
