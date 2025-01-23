<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Support\Facades\Log;
use Net_EPP_Client;

class EppService
{
    private ?Net_EPP_Client $client = null;
    private array $config;
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 2;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __destruct()
    {
        $this->cleanup();
    }

    private function cleanup(): void
    {
        try {
            if ($this->client) {
                $this->client->disconnect();
                $this->client = null;
            }
        } catch (Exception $e) {
            Log::warning('EPP cleanup error', ['message' => $e->getMessage()]);
        }
    }

    public function connect(): void
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                $this->cleanup();

                $context = $this->createSslContext();

                $this->client = new Net_EPP_Client();
                $this->client->setTimeout(30);

                $connected = $this->client->connect(
                    $this->config['server'],
                    $this->config['port'],
                    30,
                    $this->config['ssl'],
                    $context
                );

                if (!$connected || !$this->client->getFrame()) {
                    throw new Exception('Failed to connect to EPP server');
                }

                $this->login();
                return;

            } catch (Exception $e) {
                $lastException = $e;
                Log::warning('EPP connection attempt failed', [
                    'attempt' => $attempt,
                    'message' => $e->getMessage()
                ]);

                if ($attempt < self::MAX_RETRIES) {
                    sleep(self::RETRY_DELAY);
                    continue;
                }
            }
        }

        throw new Exception("EPP connection failed after {self::MAX_RETRIES} attempts: {$lastException->getMessage()}");
    }

    private function createSslContext()
    {
        if (!$this->config['ssl'] || empty($this->config['certificate'])) {
            return null;
        }

        if (!file_exists($this->config['certificate'])) {
            throw new Exception('Certificate file does not exist');
        }

        return stream_context_create([
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

    private function login(): void
    {
        $xml = $this->buildLoginXml();
        $response = $this->request($xml);

        $doc = new DOMDocument();
        $doc->loadXML($response);

        $result = $doc->getElementsByTagName('result')->item(0);
        if (!$result || $result->getAttribute('code') !== '1000') {
            throw new Exception('EPP login failed');
        }
    }

    public function checkDomains(string $primaryDomain, array $additionalDomains = []): array
    {
        $xml = $this->buildDomainCheckXml($primaryDomain, $additionalDomains);
        $response = $this->request($xml);

        return $this->parseDomainCheckResponse($response);
    }

    private function buildLoginXml(): string
    {
        return sprintf(
            '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
                <command>
                    <login>
                        <clID>%s</clID>
                        <pw>%s</pw>
                        <options>
                            <version>1.0</version>
                            <lang>en</lang>
                        </options>
                        <svcs>
                            <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                            <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
                        </svcs>
                    </login>
                    <clTRID>LOGIN-%s</clTRID>
                </command>
            </epp>',
            htmlspecialchars($this->config['username']),
            htmlspecialchars($this->config['password']),
            uniqid()
        );
    }

    private function request(string $xml): string
    {
        $this->logRequest($xml);
        $response = $this->client->request($xml);
        $this->logResponse($response);

        return $response;
    }

    private function logRequest(string $xml): void
    {
        if (config('app.debug')) {
            Log::debug('EPP Request', ['xml' => $xml]);
        }
    }

    private function logResponse(string $response): void
    {
        if (config('app.debug')) {
            Log::debug('EPP Response', ['xml' => $response]);
        }
    }

    // ... Additional EPP-related methods
}
