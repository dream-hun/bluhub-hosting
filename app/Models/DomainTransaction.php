<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;

class DomainTransaction extends Model
{
    protected $fillable = [
        'domain_id',
        'user_id',
        'type',
        'amount',
        'period',
        'payment_status',
        'payment_method',
        'payment_date',
        'payment_response',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function formatedAmount(): Money
    {
        return Money::RWF($this->amount);
    }
}
