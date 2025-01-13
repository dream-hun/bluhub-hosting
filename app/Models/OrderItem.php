<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'domain_id',
        'hosting_id',
        'ssl_id',
        'quantity',
        'price',
        'total',
        'status',
        'meta',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function hosting(): BelongsTo
    {
        return $this->belongsTo(Hosting::class);
    }

    public function ssl(): BelongsTo
    {
        return $this->belongsTo(Ssl::class);
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }
}
