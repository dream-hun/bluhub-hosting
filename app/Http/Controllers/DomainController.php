<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        return view('domains.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $tld = $request->input('select', 'com');

        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a domain name');
        }

        // Clean the domain name
        $domain = strtolower(trim($query));

        // Add validation for domain name format
        if (! preg_match('/^[a-z0-9-]+$/', $domain)) {
            return redirect()->back()->with('error', 'Invalid domain name format');
        }

        try {
            // Mock domain check result for demonstration
            $results = [
                'domain' => $domain.'.'.$tld,
                'available' => rand(0, 1) === 1,
                'price' => [
                    'register' => '6.99',
                    'renew' => '17.99',
                    'transfer' => '7.99',
                ],
            ];

            return view('domains.results', [
                'searchedDomain' => $results['domain'],
                'isAvailable' => $results['available'],
                'prices' => $results['price'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check domain availability');
        }
    }

    public function results()
    {
        return view('domains.results');
    }
}
