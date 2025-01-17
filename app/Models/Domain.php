<?php

namespace App\Models;

use App\Models\Traits\DomainDateFormating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    use DomainDateFormating;

    public const AUTO_RENEW_RADIO = [
        'true' => 'Auto Renew',
        'false' => 'Disabled',
    ];

    public const STATUS_RADIO = [
        'active' => 'Active',
        'not-active' => 'Not Active',
    ];

    protected $fillable = [
        'domain',
        'tld',
        'status',
        'registered_at',
        'expiration_date',
        'transfer_date',
        'who_is_privacy',
        'auto_renew',
        'auth_code',
        'domain_pricing_id',
        'user_id',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'expiration_date' => 'datetime',
        'transfer_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function domainPricing(): BelongsTo
    {
        return $this->belongsTo(DomainPricing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function domainContacts(): HasMany
    {
        return $this->hasMany(DomainContact::class);
    }

    public function domainTransactions(): HasMany
    {
        return $this->hasMany(DomainTransaction::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(Registrar::class);
    }

    public function dnsRecords(): HasMany
    {
        return $this->hasMany(DnsRecord::class);
    }
}
