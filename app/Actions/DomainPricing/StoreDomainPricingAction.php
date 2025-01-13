<?php

namespace App\Actions\DomainPricing;

use App\Models\DomainPricing;

class StoreDomainPricingAction
{
    public function execute(array $data): DomainPricing
    {
        return DomainPricing::create($data);
    }
}
