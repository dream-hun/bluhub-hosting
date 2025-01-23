<?php

namespace App\Http\Controllers;

use App\Models\DomainPricing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DomainController extends Controller
{
    protected $eppClient;
    protected array $additionalTlds = [
        'co.rw',
        'org.rw',
        'com.rw',
        'ac.rw',
        'gov.rw'
    ];

    /**
     * Get EPP client connection
     *
     * @throws Exception
     */
    protected function getEppConnection()
    {
        try {
            Log::info('Initializing EPP connection');

            require_once app_path('Epp/Net/EPP/Client.php');
            require_once app_path('Epp/Net/EPP/Protocol.php');

            $certPath = config('services.epp.certificate');
            $useSSL = config('services.epp.ssl', true);

            $context = null;
            if ($useSSL && !empty($certPath)) {
                if (!file_exists($certPath)) {
                    Log::error('EPP Certificate not found', ['path' => $certPath]);
                    throw new Exception('Certificate file does not exist: ' . $certPath);
                }

                Log::info('Creating SSL context with certificate');
                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'local_cert' => $certPath,
                        'allow_self_signed' => true
                    ]
                ]);
            }

            Log::info('Creating EPP client');
            $client = new \Net_EPP_Client();

            Log::info('Connecting to EPP server');
            $res = $client->connect(
                config('services.epp.server'),
                config('services.epp.port'),
                30, // Reduced timeout
                $useSSL,
                $context
            );

            if (!$res) {
                Log::error('Failed to connect to EPP server');
                throw new Exception('Failed to connect to EPP server');
            }

            Log::info('Connected to EPP server, sending login request');

            // Get greeting first
            $greeting = $client->getFrame();
            Log::debug('EPP Greeting', ['greeting' => $greeting]);

            // Login to EPP with proper XML format
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <command>
        <login>
            <clID>' . htmlspecialchars(config('services.epp.username')) . '</clID>
            <pw>' . htmlspecialchars(config('services.epp.password')) . '</pw>
            <options>
                <version>1.0</version>
                <lang>en</lang>
            </options>
            <svcs>
                <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
                <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
            </svcs>
        </login>
        <clTRID>LOGIN-' . mt_rand() . mt_rand() . '</clTRID>
    </command>
</epp>';

            Log::debug('EPP Login Request', [
                'request' => preg_replace('/<pw>.*?<\/pw>/', '<pw>********</pw>', $xml)
            ]);

            $response = $client->request($xml);
            Log::debug('EPP Login Response', ['response' => $response]);

            $doc = new \DOMDocument();
            $doc->loadXML($response);
            $result = $doc->getElementsByTagName('result')->item(0);

            if (!$result) {
                Log::error('Invalid EPP login response');
                throw new Exception('Invalid EPP login response');
            }

            $code = $result->getAttribute('code');
            $msg = $doc->getElementsByTagName('msg')->item(0)->nodeValue;

            if ($code !== '1000') {
                Log::error('EPP login failed', [
                    'code' => $code,
                    'message' => $msg
                ]);
                throw new Exception("EPP login failed: [$code] $msg");
            }

            Log::info('Successfully logged in to EPP server');
            return $client;

        } catch (Exception $e) {
            Log::error('EPP Connection Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get valid TLD list
     */
    protected function getValidTlds(): array
    {
        return array_merge(['rw'], $this->additionalTlds);
    }

    /**
     * Show the domain search form
     */
    public function index(): View
    {
        $domains = DomainPricing::all();
        $popularDomains = DomainPricing::all();

        return view('domains.search', [
            'domains' => $domains,
            'popularDomains' => $popularDomains
        ]);
    }

    /**
     * Search for domain availability
     */
    public function search(Request $request): View
    {
        try {
            Log::info('Starting domain availability check', [
                'domain' => $request->input('domain'),
                'extension' => $request->input('extension')
            ]);

            $validTlds = $this->getValidTlds();

            $validated = $request->validate([
                'domain' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z0-9-]+$/'],
                'extension' => ['required', 'string', 'in:' . implode(',', $validTlds)],
            ]);

            $domainName = strtolower($validated['domain']);
            $extension = $validated['extension'];

            Log::info('Getting EPP connection');
            $this->eppClient = $this->getEppConnection();

            // Build domain check string
            $domainString = '';
            $domains = [];

            // Add primary domain
            $domains[] = $domainName . '.' . $extension;

            // Add alternative domains
            foreach ($this->additionalTlds as $tld) {
                if ($tld !== $extension) {
                    $domains[] = $domainName . '.' . $tld;
                }
            }

            Log::info('Checking domain availability', [
                'primaryDomain' => $domainName . '.' . $extension,
                'domains' => $domains
            ]);

            // Check domain availability with proper XML format
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <command>
        <check>
            <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0"
                         xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
                ' . $domainString . '
            </domain:check>
        </check>
        <clTRID>CHECK-' . mt_rand() . mt_rand() . '</clTRID>
    </command>
</epp>';

            Log::debug('EPP Check Request', ['request' => $xml]);
            $response = $this->eppClient->request($xml);
            Log::debug('EPP Check Response', ['response' => $response]);

            // Parse response
            $doc = new \DOMDocument();
            $doc->loadXML($response);

            $result = $doc->getElementsByTagName('result')->item(0);
            if (!$result) {
                Log::error('Invalid EPP check response');
                throw new Exception('Invalid response from EPP server');
            }

            $responseCode = $result->getAttribute('code');
            $msg = $doc->getElementsByTagName('msg')->item(0)->nodeValue;

            if ($responseCode !== '1000') {
                Log::error('EPP check command failed', [
                    'code' => $responseCode,
                    'message' => $msg,
                    'response' => $response
                ]);
                throw new Exception("EPP check failed: [$responseCode] $msg");
            }

            // Process domain check results
            $xpath = new \DOMXPath($doc);
            $xpath->registerNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
            $xpath->registerNamespace('epp', 'urn:ietf:params:xml:ns:epp-1.0');

            $domainNodes = $xpath->query("//domain:cd/domain:name");
            if (!$domainNodes || $domainNodes->length === 0) {
                Log::error('No domain check data in response');
                throw new Exception('No domain check data found');
            }

            $primaryAvailable = false;
            $alternatives = [];
            $primaryDomain = $domainName . '.' . $extension;

            foreach ($domainNodes as $domainNode) {
                $checkedDomain = $domainNode->nodeValue;
                $available = $domainNode->getAttribute('avail') === '1';
                $reason = $xpath->query("../domain:reason", $domainNode)->item(0);

                Log::debug('Domain check result', [
                    'domain' => $checkedDomain,
                    'available' => $available,
                    'reason' => $reason ? $reason->nodeValue : null
                ]);

                if ($checkedDomain === $primaryDomain) {
                    $primaryAvailable = $available;
                } elseif ($available) {
                    $alternatives[] = $checkedDomain;
                }
            }

            Log::info('Domain check completed', [
                'domain' => $primaryDomain,
                'available' => $primaryAvailable,
                'alternatives' => $alternatives
            ]);

            // Get domain pricing
            $pricing = DomainPricing::whereIn('extension', array_merge(['.' . $extension], $this->additionalTlds))
                ->get()
                ->keyBy('extension');

            return view('domains.search-results', [
                'domain' => $primaryDomain,
                'available' => $primaryAvailable,
                'alternativeDomains' => $alternatives,
                'domainPricing' => $pricing,
                'error' => null
            ]);

        } catch (Exception $e) {
            Log::error('Domain search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'domain' => $request->input('domain'),
                'extension' => $request->input('extension')
            ]);

            return view('domains.search-results', [
                'domain' => $request->input('domain') . $request->input('extension'),
                'available' => false,
                'alternativeDomains' => [],
                'domainPricing' => collect(),
                'error' => $e->getMessage()
            ]);

        } finally {
            if (isset($this->eppClient)) {
                try {
                    if ($this->eppClient->socket && is_resource($this->eppClient->socket)) {
                        $this->eppClient->disconnect();
                    }
                } catch (Exception $e) {
                    Log::warning('EPP Disconnect Error', [
                        'message' => $e->getMessage()
                    ]);
                }
                $this->eppClient = null;
            }
        }
    }

    /**
     * Show the domain registration form
     */
    public function showRegistrationForm(Request $request): View
    {
        return view('domains.register', [
            'domain' => $request->input('domain'),
            'extension' => $request->input('extension')
        ]);
    }
}
