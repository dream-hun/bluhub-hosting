<?php

namespace App\Services\OpenXML;

use DOMDocument;
use DOMException;

class OP_Request
{
    protected $cmd = null;

    protected $args = null;

    protected $username = null;

    protected $password = null;

    protected $hash = null;

    protected $token = null;

    protected $ip = null;

    protected $language = null;

    protected $raw = null;

    protected $dom = null;

    protected $misc = null;

    protected $filters = [];

    public function __construct($str = null)
    {
        if ($str) {
            $this->setContent($str);
        }
    }

    public function addFilter($filter): static
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function setContent($str): static
    {
        $this->raw = $str;

        return $this;
    }

    public function getDom()
    {
        if (! $this->dom) {
            $this->initDom();
        }

        return $this->dom;
    }

    /**
     * @throws OP_API_Exception
     */
    public function parseContent(): void
    {
        $this->initDom();
        if (! $this->dom) {
            return;
        }

        foreach ($this->filters as $f) {
            $f->filter($this);
        }

        $this->_retrieveDataFromDom($this->dom);
    }

    public function setCommand($v): static
    {
        $this->cmd = $v;

        return $this;
    }

    public function getCommand()
    {
        return $this->cmd;
    }

    public function setLanguage($v): static
    {
        $this->language = $v;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setArgs($v): static
    {
        $this->args = $v;

        return $this;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function setMisc($v): static
    {
        $this->misc = $v;

        return $this;
    }

    public function getMisc()
    {
        return $this->misc;
    }

    public function setAuth($args): static
    {
        $this->username = $args['username'] ?? null;
        $this->password = $args['password'] ?? null;
        $this->hash = $args['hash'] ?? null;
        $this->token = $args['token'] ?? null;
        $this->ip = $args['ip'] ?? null;
        $this->misc = $args['misc'] ?? null;

        return $this;
    }

    public function getAuth(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'hash' => $this->hash,
            'token' => $this->token,
            'ip' => $this->ip,
            'misc' => $this->misc,
        ];
    }

    /**
     * @throws DOMException
     */
    public function getRaw()
    {
        if (! $this->raw) {
            $this->raw = $this->_getRequest();
        }

        return $this->raw;
    }

    /**
     * @throws DOMException
     */
    public function _getRequest(): false|string
    {
        $dom = new DOMDocument('1.0', OP_API::$encoding);

        $credentialsElement = $dom->createElement('credentials');

        $usernameElement = $dom->createElement('username');
        $usernameElement->appendChild(
            $dom->createTextNode(OP_API::encode($this->username))
        );
        $credentialsElement->appendChild($usernameElement);

        $passwordElement = $dom->createElement('password');
        $passwordElement->appendChild(
            $dom->createTextNode(OP_API::encode($this->password))
        );
        $credentialsElement->appendChild($passwordElement);

        $hashElement = $dom->createElement('hash');
        $hashElement->appendChild(
            $dom->createTextNode(OP_API::encode($this->hash))
        );
        $credentialsElement->appendChild($hashElement);

        if (isset($this->language)) {
            $languageElement = $dom->createElement('language');
            $languageElement->appendChild($dom->createTextNode($this->language));
            $credentialsElement->appendChild($languageElement);
        }

        if (isset($this->token)) {
            $tokenElement = $dom->createElement('token');
            $tokenElement->appendChild($dom->createTextNode($this->token));
            $credentialsElement->appendChild($tokenElement);
        }

        if (isset($this->ip)) {
            $ipElement = $dom->createElement('ip');
            $ipElement->appendChild($dom->createTextNode($this->ip));
            $credentialsElement->appendChild($ipElement);
        }

        if (isset($this->misc)) {
            $miscElement = $dom->createElement('misc');
            $credentialsElement->appendChild($miscElement);
            OP_API::convertPhpObjToDom($this->misc, $miscElement, $dom);
        }

        $rootElement = $dom->createElement('openXML');
        $rootElement->appendChild($credentialsElement);

        $rootNode = $dom->appendChild($rootElement);
        $cmdNode = $rootNode->appendChild(
            $dom->createElement($this->getCommand())
        );
        OP_API::convertPhpObjToDom($this->args, $cmdNode, $dom);

        return $dom->saveXML();
    }

    protected function initDom(): void
    {
        if ($this->raw) {
            $this->dom = new DOMDocument;
            $this->dom->loadXML($this->raw, LIBXML_NOBLANKS);
        }
    }

    protected function setDom($dom): static
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * @throws OP_API_Exception
     */
    protected function _retrieveDataFromDom($dom): void
    {
        $arr = OP_API::convertXmlToPhpObj($dom->documentElement);
        [$dummy, $credentials] = each($arr);
        [$this->cmd, $this->args] = each($arr);

        $this->username = $credentials['username'];
        $this->password = $credentials['password'];

        if (isset($credentials['hash'])) {
            $this->hash = $credentials['hash'];
        }

        if (isset($credentials['misc'])) {
            $this->misc = $credentials['misc'];
        }

        $this->token = $credentials['token'] ?? null;
        $this->ip = $credentials['ip'] ?? null;

        if (isset($credentials['language'])) {
            $this->language = $credentials['language'];
        }
    }
}
