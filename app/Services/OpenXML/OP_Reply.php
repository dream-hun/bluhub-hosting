<?php

namespace App\Services\OpenXML;

use DOMDocument;
use DOMException;

class OP_Reply
{
    protected int $faultCode = 0;

    protected mixed $faultString = null;

    protected array $value = [];

    protected array $warnings = [];

    protected mixed $raw = null;

    protected mixed $dom = null;

    protected array $filters = [];

    protected mixed $maintenance = null;

    /**
     * @throws OP_API_Exception
     */
    public function __construct($str = null)
    {
        if ($str) {
            $this->raw = $str;
            $this->_parseReply($str);
        }
    }

    public function encode($str)
    {
        return OP_API::encode($str);
    }

    public function setFaultCode($v): static
    {
        $this->faultCode = $v;

        return $this;
    }

    public function setFaultString($v): static
    {
        $this->faultString = $v;

        return $this;
    }

    public function setValue($v): static
    {
        $this->value = $v;

        return $this;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setWarnings($v): static
    {
        $this->warnings = $v;

        return $this;
    }

    public function getDom()
    {
        return $this->dom;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getMaintenance()
    {
        return $this->maintenance;
    }

    public function getFaultString()
    {
        return $this->faultString;
    }

    public function getFaultCode(): int
    {
        return $this->faultCode;
    }

    /**
     * @throws DOMException
     */
    public function getRaw()
    {
        if (! $this->raw) {
            $this->raw = $this->_getReply();
        }

        return $this->raw;
    }

    public function addFilter($filter): static
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Check if the response has any warnings
     */
    public function hasWarnings(): bool
    {
        return ! empty($this->warnings);
    }

    /**
     * Check if the response indicates maintenance mode
     */
    public function isInMaintenance(): bool
    {
        return $this->maintenance !== null;
    }

    /**
     * Check if the response was successful
     */
    public function isSuccessful(): bool
    {
        return $this->faultCode === 0;
    }

    /**
     * @throws OP_API_Exception
     */
    protected function _parseReply($str = ''): void
    {
        $dom = new DOMDocument;
        $result = $dom->loadXML(trim($str));

        if (! $result) {
            logger()->error("Cannot parse xml: '{$str}'");
        }

        $arr = OP_API::convertXmlToPhpObj($dom->documentElement);

        if ((! is_array($arr) && trim($arr) === '') ||
            $arr['reply']['code'] === 4005
        ) {
            throw new OP_API_Exception('API is temporarily unavailable due to maintenance', 4005);
        }

        $this->faultCode = (int) $arr['reply']['code'];
        $this->faultString = $arr['reply']['desc'];
        $this->value = $arr['reply']['data'] ?? [];

        if (isset($arr['reply']['warnings'])) {
            $this->warnings = $arr['reply']['warnings'];
        }

        if (isset($arr['reply']['maintenance'])) {
            $this->maintenance = $arr['reply']['maintenance'];
        }
    }

    /**
     * @throws DOMException
     */
    protected function _getReply(): false|string
    {
        $dom = new DOMDocument('1.0', OP_API::$encoding);
        $rootNode = $dom->appendChild($dom->createElement('openXML'));
        $replyNode = $rootNode->appendChild($dom->createElement('reply'));

        // Add code element
        $codeNode = $replyNode->appendChild($dom->createElement('code'));
        $codeNode->appendChild($dom->createTextNode($this->faultCode));

        // Add description element
        $descNode = $replyNode->appendChild($dom->createElement('desc'));
        $descNode->appendChild(
            $dom->createTextNode(OP_API::encode($this->faultString))
        );

        // Add data element
        $dataNode = $replyNode->appendChild($dom->createElement('data'));
        OP_API::convertPhpObjToDom($this->value, $dataNode, $dom);

        // Add warnings if present
        if (count($this->warnings) > 0) {
            $warningsNode = $replyNode->appendChild($dom->createElement('warnings'));
            OP_API::convertPhpObjToDom($this->warnings, $warningsNode, $dom);
        }

        // Add maintenance info if present
        if ($this->maintenance !== null) {
            $maintenanceNode = $replyNode->appendChild($dom->createElement('maintenance'));
            OP_API::convertPhpObjToDom($this->maintenance, $maintenanceNode, $dom);
        }

        $this->dom = $dom;

        // Apply any filters
        foreach ($this->filters as $f) {
            $f->filter($this);
        }

        return $dom->saveXML();
    }
}
