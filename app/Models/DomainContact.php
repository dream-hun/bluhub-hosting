<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainContact extends Model
{
    protected $fillable = [
        'domain_id',
        'type',
        'name',
        'organization',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'country',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
