<?php

namespace App\Services\OpenXML;

use DOMDocument;
use DOMException;

class OP_API
{
    public static string $encoding = 'UTF-8';

    protected mixed $url = null;

    protected $error = null;

    protected mixed $timeout = null;

    protected $debug = null;

    public function __construct($url = null, $timeout = 1000)
    {
        $this->url = $url;
        $this->timeout = $timeout;
    }

    public function setDebug($v): static
    {
        $this->debug = $v;

        return $this;
    }

    /**
     * @throws OP_API_Exception|DOMException
     */
    public function processRawReply(OP_Request $r): bool|string
    {
        if ($this->debug) {
            logger()->debug($r->getRaw());
        }

        $msg = $r->getRaw();
        $str = $this->_send($msg);

        if (! $str) {
            throw new OP_API_Exception('Bad reply', 4004);
        }

        if ($this->debug) {
            logger()->debug($str);
        }

        return $str;
    }

    /**
     * @throws OP_API_Exception|DOMException
     */
    public function process(OP_Request $r): OP_Reply
    {
        if ($this->debug) {
            logger()->debug($r->getRaw());
        }

        $msg = $r->getRaw();
        $str = $this->_send($msg);

        if (! $str) {
            throw new OP_API_Exception('Bad reply', 4004);
        }

        if ($this->debug) {
            logger()->debug($str);
        }

        return new OP_Reply($str);
    }

    /**
     * @throws DOMException
     */
    public static function checkCreateXml($str): bool
    {
        $dom = new DOMDocument;
        $dom->encoding = 'utf-8';

        $textNode = $dom->createTextNode($str);

        $element = $dom->createElement('element')
            ->appendChild($textNode);

        if (! $element) {
            return false;
        }

        @$dom->appendChild($element);

        $xml = $dom->saveXML();

        return ! empty($xml);
    }

    /**
     * @throws DOMException
     */
    public static function encode($str)
    {
        $ret = @htmlentities($str, null, self::$encoding);

        // Some tables have data stored in two encodings
        if (strlen($str) && ! strlen($ret)) {
            logger()->error('ISO charset date = '.date('d.m.Y H:i:s').',STR = '.$str);
            $str = iconv('ISO-8859-1', 'UTF-8', $str);
        }

        if (! empty($str) && is_object($str)) {
            logger()->error('Exception convertPhpObjToDom date = '.date('d.m.Y H:i:s').', object class = '.$str::class);
            if (method_exists($str, '__toString')) {
                $str = $str->__toString();
            } else {
                return $str;
            }
        }

        if (! empty($str) && is_string($str) && ! self::checkCreateXml($str)) {
            logger()->error('Exception convertPhpObjToDom date = '.date('d.m.Y H:i:s').', STR = '.$str);
            $str = htmlentities($str, null, self::$encoding);
        }

        return $str;
    }

    public static function decode($str)
    {
        return $str;
    }

    public static function createRequest($xmlStr = null): OP_Request
    {
        return new OP_Request($xmlStr);
    }

    /**
     * @throws OP_API_Exception
     */
    public static function createReply($xmlStr = null): OP_Reply
    {
        return new OP_Reply($xmlStr);
    }

    /**
     * @throws OP_API_Exception
     */
    public static function convertXmlToPhpObj($node)
    {
        $ret = [];

        if (is_object($node) && $node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $name = self::decode($child->nodeName);
                if ($child->nodeType === XML_TEXT_NODE) {
                    $ret = self::decode($child->nodeValue);
                } else {
                    if ($name === 'array') {
                        return self::parseArray($child);
                    }
                    $ret[$name] = self::convertXmlToPhpObj($child);
                }
            }
        }

        if (is_string($ret)) {
            return strlen($ret) > 0 ? $ret : null;
        }
        if (is_array($ret)) {
            return ! empty($ret) ? $ret : null;
        }
        if (is_null($ret)) {
            return null;
        }

        return false;
    }

    /**
     * @throws DOMException
     */
    public static function convertPhpObjToDom($arr, $node, $dom): void
    {
        if (is_array($arr)) {
            $arrayParam = [];
            foreach ($arr as $k => $v) {
                if (is_int($k)) {
                    $arrayParam[] = $v;
                }
            }

            if (count($arrayParam) > 0) {
                $node->appendChild($arrayDom = $dom->createElement('array'));
                foreach ($arrayParam as $val) {
                    $new = $arrayDom->appendChild($dom->createElement('item'));
                    self::convertPhpObjToDom($val, $new, $dom);
                }
            } else {
                foreach ($arr as $key => $val) {
                    $new = $node->appendChild(
                        $dom->createElement(self::encode($key))
                    );
                    self::convertPhpObjToDom($val, $new, $dom);
                }
            }
        } elseif (! is_object($arr)) {
            $node->appendChild($dom->createTextNode(self::encode($arr)));
        }
    }

    protected function _send($str): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $ret = curl_exec($ch);
        $errno = curl_errno($ch);
        $this->error = $error = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            logger()->error("CURL error. Code: {$errno}, Message: {$error}");

            return false;
        }

        return $ret;
    }

    /**
     * @throws OP_API_Exception
     */
    protected static function parseArray($node): array
    {
        $ret = [];
        foreach ($node->childNodes as $child) {
            $name = self::decode($child->nodeName);
            if ($name !== 'item') {
                throw new OP_API_Exception('Wrong message format', 4006);
            }
            $ret[] = self::convertXmlToPhpObj($child);
        }

        return $ret;
    }
}
