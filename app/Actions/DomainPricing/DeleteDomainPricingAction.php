<?php

namespace App\Actions\DomainPricing;

use App\Models\DomainPricing;

class DeleteDomainPricingAction
{
    public function execute(DomainPricing $pricing): void
    {
        $pricing->delete();
    }
}
