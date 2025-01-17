<?php

namespace App\Http\Controllers;

use App\Services\OpenXML\OP_API;
use App\Services\OpenXML\OP_Request;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    protected OP_API $api;

    public function __construct()
    {
        // Initialize API with the OpenProvider URL
        $this->api = new OP_API('https://api.openprovider.eu');
        $this->api->setDebug(config('openxml.debug', false));
    }

    /**
     * Show the search form
     */
    public function search(): View
    {
        return view('domains.search');
    }

    /**
     * Check domain availability
     */
    public function check(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:2|max:255',
            'tld' => 'required|string|min:2|max:63',
        ]);

        try {
            // Create OpenProvider request
            $apiRequest = new OP_Request;
            $apiRequest->setCommand('checkDomainRequest')
                ->setAuth([
                    'username' => config('openxml.username'),
                    'password' => config('openxml.password'),
                ])
                ->setArgs([
                    'domains' => [
                        [
                            'name' => $request->input('domain'),
                            'extension' => $request->input('tld'),
                        ],
                    ],
                ]);

            $reply = $this->api->process($apiRequest);

            if ($reply->getFaultCode() !== 0) {
                return back()->with('error', $reply->getFaultString());
            }

            $results = $reply->getValue();

            // Check if domain exists in results
            if (! empty($results['results'])) {
                $domainResult = $results['results'][0];

                return back()->with([
                    'domain' => $request->input('domain').'.'.$request->input('tld'),
                    'available' => $domainResult['status'] === 'free',
                    'price' => $domainResult['price'] ?? null,
                    'premium' => $domainResult['premium'] ?? false,
                    'message' => $domainResult['message'] ?? null,
                ]);
            }

            return back()->with('error', 'No results found for this domain');
        } catch (Exception $e) {
            report($e); // Log the error

            return back()->with('error', 'An error occurred while checking domain availability: '.$e->getMessage());
        }
    }

    /**
     * Check multiple domains at once
     */
    public function bulkCheck(Request $request)
    {
        $request->validate([
            'domains' => 'required|array|min:1',
            'domains.*.name' => 'required|string|min:2|max:255',
            'domains.*.extension' => 'required|string|min:2|max:63',
        ]);

        try {
            $apiRequest = new OP_Request;
            $apiRequest->setCommand('checkDomainRequest')
                ->setAuth([
                    'username' => config('openxml.username'),
                    'password' => config('openxml.password'),
                ])
                ->setArgs([
                    'domains' => $request->input('domains'),
                ]);

            $reply = $this->api->process($apiRequest);

            if ($reply->getFaultCode() !== 0) {
                return back()->with('error', $reply->getFaultString());
            }

            $results = $reply->getValue();

            return back()->with([
                'results' => $results['results'] ?? [],
                'success' => true,
            ]);
        } catch (Exception $e) {
            report($e); // Log the error

            return back()->with('error', 'An error occurred while checking domains: '.$e->getMessage());
        }
    }
}
