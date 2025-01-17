<?php

namespace App\Http\Controllers;

use App\Models\DomainPricing;

class SearchDomainController extends Controller
{
    public function index()
    {
        $tlds = DomainPricing::select(['tld', 'registration_price', 'renewal_price'])
            ->limit(5)
            ->get();
        $popularDomains = DomainPricing::select(['tld', 'registration_price'])->inRandomOrder()->limit(5)->get();
        $allTlds = DomainPricing::select(['tld', 'registration_price', 'renewal_price'])->get();

        return view('domains.search', ['tlds' => $tlds, 'popularDomains' => $popularDomains, 'allTlds' => $allTlds]);
    }

    public function search() {}
}
