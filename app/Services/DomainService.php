<?php

namespace App\Services;

use App\Models\DomainPricing;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DomainService
{
    protected ?\Net_EPP_Client $eppClient = null;
    protected array $config;

    public function __construct()
    {
        $this->config = [
            'username' => config('services.epp.username'),
            'password' => config('services.epp.password'),
            'server' => config('services.epp.server'),
            'port' => config('services.epp.port'),
            'certificate' => config('services.epp.certificate'),
            'ssl' => config('services.epp.ssl', true),
        ];
    }

    public function __destruct()
    {
        $this->cleanup();
    }

    protected function cleanup(): void
    {
        try {
            if ($this->eppClient !== null) {
                try {
                    // Only disconnect if we have a valid socket
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
        } catch (Exception $e) {
            Log::warning('EPP Cleanup Error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Initialize EPP client connection with retry mechanism
     *
     * @throws Exception
     */
    protected function initializeConnection(): void
    {
        $maxRetries = 3;
        $retryDelay = 2;
        $lastException = null;

        $this->cleanup();

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                require_once app_path('Epp/Net/EPP/Client.php');
                require_once app_path('Epp/Net/EPP/Protocol.php');

                $context = null;
                if ($this->config['ssl'] && !empty($this->config['certificate'])) {
                    if (!file_exists($this->config['certificate'])) {
                        throw new Exception('Certificate file does not exist.');
                    }

                    $context = stream_context_create([
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'local_cert' => $this->config['certificate'],
                        ],
                        'socket' => [
                            'tcp_nodelay' => true,
                            'keepalive' => true,
                            'tcp_keepidle' => 60,
                            'tcp_keepintvl' => 10,
                            'tcp_keepcnt' => 5
                        ]
                    ]);
                }

                $this->eppClient = new \Net_EPP_Client();
                
                $timeout = 30;
                
                $res = $this->eppClient->connect(
                    $this->config['server'],
                    $this->config['port'],
                    $timeout,
                    $this->config['ssl'],
                    $context
                );

                if (!$res) {
                    throw new Exception('Failed to connect to EPP server');
                }

                if (!$this->eppClient->socket || !is_resource($this->eppClient->socket)) {
                    throw new Exception('Invalid socket connection');
                }

                $greeting = $this->eppClient->getFrame();
                if (!$greeting) {
                    throw new Exception('No greeting received from EPP server');
                }

                $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <command>
        <login>
            <clID>' . htmlspecialchars($this->config['username']) . '</clID>
            <pw>' . htmlspecialchars($this->config['password']) . '</pw>
            <options>
                <version>1.0</version>
                <lang>en</lang>
            </options>
            <svcs>
                <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
            </svcs>
        </login>
        <clTRID>LOGIN-' . mt_rand() . mt_rand() . '</clTRID>
    </command>
</epp>';

                $response = $this->eppClient->request($xml);

                if (config('app.debug')) {
                    $logPath = storage_path('logs/epp');
                    if (!file_exists($logPath)) {
                        mkdir($logPath, 0755, true);
                    }
                    
                    file_put_contents(
                        $logPath . '/login-' . date('Y-m-d') . '.log',
                        '[' . date('Y-m-d H:i:s') . '] Attempt: ' . $attempt . PHP_EOL .
                        '[' . date('Y-m-d H:i:s') . '] Login Request: ' . $xml . PHP_EOL .
                        '[' . date('Y-m-d H:i:s') . '] Login Response: ' . $response . PHP_EOL,
                        FILE_APPEND
                    );
                }

                $doc = new \DOMDocument();
                $doc->loadXML($response);
                $result = $doc->getElementsByTagName('result')->item(0);
                
                if (!$result) {
                    throw new Exception('Invalid login response from EPP server');
                }

                $code = $result->getAttribute('code');
                $msg = $doc->getElementsByTagName('msg')->item(0)->nodeValue;

                if ($code !== '1000') {
                    throw new Exception("EPP login failed: [$code] $msg");
                }

                return;

            } catch (Exception $e) {
                $this->cleanup();
                
                $lastException = $e;
                Log::warning('EPP Connection Attempt ' . $attempt . ' Failed', [
                    'message' => $e->getMessage(),
                    'attempt' => $attempt,
                    'maxRetries' => $maxRetries
                ]);

                if ($attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue;
                }
            }
        }

        Log::error('EPP Connection Error - All Attempts Failed', [
            'message' => $lastException->getMessage(),
            'trace' => $lastException->getTraceAsString(),
            'config' => array_merge(
                $this->config,
                ['password' => '********']
            ),
            'attempts' => $maxRetries
        ]);
        
        throw new Exception('EPP Connection Error after ' . $maxRetries . ' attempts: ' . $lastException->getMessage());
    }

    /**
     * Check domain availability and get alternative suggestions
     *
     * @param string $domain Base domain name
     * @param string $primaryExtension Primary TLD to check
     * @param array $additionalTlds Additional TLDs to check
     * @return array{primary: bool, alternatives: array<string>}
     * @throws Exception
     */
    public function checkAvailability(string $domain, string $primaryExtension, array $additionalTlds = []): array
    {
        try {
            $this->initializeConnection();

            $domainString = '<domain:name>' . htmlspecialchars($domain . $primaryExtension) . '</domain:name>';
            foreach ($additionalTlds as $tld) {
                if ($tld !== $primaryExtension) {
                    $domainString .= '<domain:name>' . htmlspecialchars($domain . $tld) . '</domain:name>';
                }
            }

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <command>
        <check>
            <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                ' . $domainString . '
            </domain:check>
        </check>
        <clTRID>CHECK-' . mt_rand() . mt_rand() . '</clTRID>
    </command>
</epp>';

            $response = $this->eppClient->request($xml);

            if (config('app.debug')) {
                $logPath = storage_path('logs/epp');
                file_put_contents(
                    $logPath . '/domain-check-' . date('Y-m-d') . '.log',
                    '[' . date('Y-m-d H:i:s') . '] Check Request: ' . $xml . PHP_EOL .
                    '[' . date('Y-m-d H:i:s') . '] Raw Response: ' . $response . PHP_EOL,
                    FILE_APPEND
                );
            }

            $doc = new \DOMDocument();
            $doc->loadXML($response);

            $result = $doc->getElementsByTagName('result')->item(0);
            if (!$result) {
                throw new Exception('Invalid response from EPP server: Missing result code');
            }

            $responseCode = $result->getAttribute('code');
            $msg = $doc->getElementsByTagName('msg')->item(0)->nodeValue;

            if ($responseCode !== '1000') {
                Log::error('EPP Command Error', [
                    'code' => $responseCode,
                    'message' => $msg,
                    'request' => $xml,
                    'response' => $response
                ]);
                throw new Exception("EPP server error: [$responseCode] $msg");
            }

            $xpath = new \DOMXPath($doc);
            $xpath->registerNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
            $xpath->registerNamespace('epp', 'urn:ietf:params:xml:ns:epp-1.0');
            
            $domainNodes = $xpath->query("//domain:chkData/domain:cd/domain:name");
            
            if (!$domainNodes || $domainNodes->length === 0) {
                throw new Exception('Invalid response from EPP server: Missing domain check data');
            }

            $primaryAvailable = false;
            $alternatives = [];

            foreach ($domainNodes as $domainNode) {
                $checkedDomain = $domainNode->nodeValue;
                $available = $domainNode->getAttribute('avail') === '1';
                
                if ($checkedDomain === $domain . $primaryExtension) {
                    $primaryAvailable = $available;
                } elseif ($available) {
                    $alternatives[] = $checkedDomain;
                }
            }

            return [
                'primary' => $primaryAvailable,
                'alternatives' => $alternatives
            ];

        } finally {
            $this->cleanup();
        }
    }

    /**
     * Get pricing information for domain extensions
     *
     * @param array $extensions List of domain extensions to get pricing for
     * @return Collection
     */
    public function getPricing(array $extensions): Collection
    {
        return DomainPricing::whereIn('extension', $extensions)
            ->get()
            ->keyBy('extension');
    }
}
