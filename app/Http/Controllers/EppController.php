<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\DomainPricing;
use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Support\Facades\Log;
use Net_EPP_Client;
use Net_EPP_Protocol;

/**
 * Search for domain availability
 *
 * @param Request $request
 * @return JsonResponse
 */
class EppController extends Controller
{
    private array $params;

    public function __construct()
    {
        require_once(base_path('app/Epp/client/Net/EPP/Client.php'));
        require_once(base_path('app/Epp/client/Net/EPP/Protocol.php'));
        $this->params = [
            "Username" => config('epp.username'),
            "Password" => config('epp.password'),
            "Server" => config('epp.server'),
            "Port" => config('epp.port'),
            "Certificate" => config('epp.certificate_path'),
            "SSL" => config('epp.ssl_enabled', 'on')
        ];
    }

    public function index()
    {
        $domains = DomainPricing::orderBy('tld')->get();


        return view('domains.search', [
            'domains' => $domains,

        ]);
    }

    /**
     * Search for domain availability
     *
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'domain' => ['required', 'string', 'min:3', 'regex:/^[a-zA-Z0-9-]+$/'],
                'extension' => ['required', 'string']
            ]);

            $domainText = $request->input('domain');
            $extension = $request->input('extension');

            $client = $this->getEppConnection();
            $domainString = $this->buildDomainString($domainText, $extension);

            $response = $this->performDomainCheck($client, $domainString);
            $domainResults = $this->processDomainCheckResponse($response);

            $domains = DomainPricing::orderBy('tld')->get();
            $popularDomains = DomainPricing::inRandomOrder(5)->get();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $domainResults
                ]);
            }

            return view('domains.search', [
                'searchResults' => $domainResults,
                'domains' => $domains,
                'popularDomains' => $popularDomains,
                'searchedDomain' => $domainText,
                'searchedExtension' => $extension
            ]);

        } catch (Exception $e) {
            Log::error('EPP Error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to process domain check',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to process domain check: ' . $e->getMessage());
        }
    }

    /**
     * Get EPP Client connection
     *
     * @return Net_EPP_Client
     * @throws Exception
     */
    private function getEppConnection()
    {
        $useSSL = ($this->params['SSL'] === 'on');
        $context = null;

        if ($useSSL && !empty($this->params['Certificate'])) {
            if (!file_exists($this->params['Certificate'])) {
                throw new Exception('Certificate file does not exist.');
            }

            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            stream_context_set_option($context, 'ssl', 'local_cert', $this->params['Certificate']);
        }

        $client = new Net_EPP_Client();
        $client->connect(
            $this->params['Server'],
            $this->params['Port'],
            60,
            $useSSL,
            $context
        );

        // Perform login
        $loginXml = $this->buildLoginXml();
        $response = $client->request($loginXml);

        $this->logEppTransaction('login', $loginXml, $response);

        return $client;
    }

    /**
     * Build domain string for checking multiple TLDs
     *
     * @param string $domainText
     * @param string $extension
     * @return string
     */
    private function buildDomainString(string $domainText, string $extension)
    {
        $domainString = "<domain:name>{$domainText}.{$extension}</domain:name>";
        $additionalTlds = ['.co.rw', '.org.rw'];

        foreach ($additionalTlds as $tld) {
            if ($tld !== $extension) {
                $domainString .= "<domain:name>{$domainText}{$tld}</domain:name>";
            }
        }

        return $domainString;
    }

    /**
     * Perform domain check request
     *
     * @param Net_EPP_Client $client
     * @param string $domainString
     * @return string
     * @throws Exception
     */
    private function performDomainCheck(Net_EPP_Client $client, string $domainString)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <check>
                    <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                        ' . $domainString . '
                    </domain:check>
                </check>
                <clTRID>' . mt_rand() . mt_rand() . '</clTRID>
            </command>
        </epp>';

        $response = $client->request($xml);
        $this->logEppTransaction('domain-check', $xml, $response);

        return $response;
    }

    /**
     * Process domain check response
     *
     * @param string $response
     * @return array
     * @throws Exception
     */
    private function processDomainCheckResponse(string $response)
    {
        $xmlResponse = new DOMDocument("1.0");
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000') {
            throw new Exception("EPP Error: {$message}");
        }

        $domainStatuses = [];
        $domXPath = new DOMXPath($xmlResponse);
        $domXPath->registerNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $domainElements = $domXPath->query("//domain:chkData/domain:cd/domain:name");

        foreach ($domainElements as $index => $element) {
            $domainStatuses[] = [
                'domain_name' => $element->textContent,
                'is_available' => $element->getAttribute('avail') === '1',
                'is_primary' => $index === 0,
                'selling_cost' => 'Not yet implemented'
            ];
        }

        return $domainStatuses;
    }

    /**
     * Build login XML
     *
     * @return string
     */
    private function buildLoginXml()
    {
        return '
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <login>
                    <clID>' . $this->params['Username'] . '</clID>
                    <pw>' . $this->params['Password'] . '</pw>
                    <options>
                        <version>1.0</version>
                        <lang>en</lang>
                    </options>
                    <svcs>
                        <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                        <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
                    </svcs>
                </login>
            </command>
        </epp>';
    }

    /**
     * Log EPP transactions
     *
     * @param string $type
     * @param string $request
     * @param string $response
     * @return void
     */
    private function logEppTransaction($type, $request, $response)
    {
        $logPath = storage_path('logs/epp');
        if (!file_exists($logPath)) {
            mkdir($logPath, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        file_put_contents("{$logPath}/{$type}-request-{$timestamp}.xml", $request);
        file_put_contents("{$logPath}/{$type}-response-{$timestamp}.xml", $response);
    }
}
