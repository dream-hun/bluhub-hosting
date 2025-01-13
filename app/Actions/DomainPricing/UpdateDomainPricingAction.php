<?php

namespace App\Actions\DomainPricing;

use App\Models\DomainPricing;

class UpdateDomainPricingAction
{
    public function execute(DomainPricing $pricing, array $data): DomainPricing
    {
        $pricing->update($data);

        return $pricing;
    }
}
