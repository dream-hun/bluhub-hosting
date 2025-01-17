<?php

namespace Database\Seeders;

use App\Models\DomainPricing;
use Illuminate\Database\Seeder;

class DomainPricingSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            [
                'tld' => '.com',
                'registration_price' => 16500, // ~$13
                'renewal_price' => 18750,      // ~$15
                'transfer_price' => 15000,     // ~$12
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.net',
                'registration_price' => 17500,
                'renewal_price' => 20000,
                'transfer_price' => 16250,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.org',
                'registration_price' => 18750,
                'renewal_price' => 21250,
                'transfer_price' => 17500,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.info',
                'registration_price' => 12500,
                'renewal_price' => 15000,
                'transfer_price' => 11250,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.biz',
                'registration_price' => 13750,
                'renewal_price' => 16250,
                'transfer_price' => 12500,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.co',
                'registration_price' => 31250,
                'renewal_price' => 33750,
                'transfer_price' => 30000,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.io',
                'registration_price' => 62500,
                'renewal_price' => 65000,
                'transfer_price' => 61250,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.dev',
                'registration_price' => 20000,
                'renewal_price' => 22500,
                'transfer_price' => 18750,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.app',
                'registration_price' => 21250,
                'renewal_price' => 23750,
                'transfer_price' => 20000,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.ai',
                'registration_price' => 100000,
                'renewal_price' => 102500,
                'transfer_price' => 98750,
                // 'grace_period' => 30,
            ],
            // Added some African TLDs
            [
                'tld' => '.rw',
                'registration_price' => 25000,
                'renewal_price' => 27500,
                'transfer_price' => 23750,
                // 'grace_period' => 30,
            ],
            [
                'tld' => '.africa',
                'registration_price' => 22500,
                'renewal_price' => 25000,
                'transfer_price' => 21250,
                // 'grace_period' => 30,
            ], [
                'tld' => '.rw',
                'registration_price' => 12000,
                'renewal_price' => 12000,
                'transfer_price' => 21250,
                // 'grace_period' => 30,
            ],
        ];

        foreach ($domains as $domain) {
            DomainPricing::updateOrCreate(
                ['tld' => $domain['tld']],
                $domain
            );
        }
    }
}
