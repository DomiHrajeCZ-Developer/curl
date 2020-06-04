<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Sender;

use Nette\Utils\Strings;
use ymastersk\Curl\Exception\InvalidOptionException;
use ymastersk\Curl\Http\Request;
use ymastersk\Curl\Http\Response;
use ymastersk\Curl\Http\ResponseFactory;
use ymastersk\Curl\Wrapper\CurlWrapper;

class CurlSender {

    /** @var Request */
    private $request;

    /** @var Response */
    private $response;

    /** @var CurlWrapper */
    private $curl;

    /** @var array */
    private $headers = [];

    /** @var array */
    private $options = [];

    public function __construct(Request $request){
        $this->request = $request;
        $this->curl = new CurlWrapper($this->request->getUrl()->getAbsoluteUrl());
        $this->options();
    }

    public function makeRequest(): Response {
        $this->response = ResponseFactory::create($this->curl->getBody(), $this->curl->getInfo(), $this->headers);
        $this->curl->close();
        if($this->request->getClient()->isLoggerEnabled())
            $this->request->getClient()->getLogger()->log($this);
        return $this->getResponse();
    }

    private function options(): void {
        $this->curl->setOption(CURLINFO_HEADER_OUT, true);
        $this->request->setOption('returnTransfer', true);
        $this->request->setOption('port', $this->request->getUrl()->getPort());
        $this->curl->setOption(CURLOPT_HEADERFUNCTION, function($curl, string $header) {
            $parts = explode(':', $header, 2);
            if(count($parts) >= 2)
                $this->headers[Strings::lower(trim($parts[0]))] = trim($parts[1]);
            return strlen($header);
        });
        $method = $this->request->getMethod();
        switch($method){
            case Request::GET:
                $this->request->setOption('httpGet', true);
                break;
            case Request::HEAD:{
                $this->request->setOption('customRequest', $method);
                $this->request->setOption('nobody', true);
                break;
            }
            case Request::POST:
                $this->request->setOption('post', true);
                break;
            default:
                $this->request->setOption('customRequest', $method);
                break;
        }
        $this->curl->setOption(CURLOPT_HTTPHEADER, $this->formatHeaders());
        $this->curl->setOption(CURLOPT_COOKIE, $this->formatCookies());
        $this->options = $this->request->getOptions();
        foreach($this->options as $option => $value){
            $opt = 'CURLOPT_' . Strings::upper($option);
            if(!defined($opt))
                throw new InvalidOptionException("Option $opt does not exist.");
            $this->curl->setOption(constant($opt), $value);
        }
        if(in_array($this->request->getMethod(), [Request::POST, Request::PATCH, Request::PUT, Request::DELETE]))
            $this->curl->setOption(CURLOPT_POSTFIELDS, $this->request->getParams());
    }

    public function getRequest(): Request {
        return $this->request;
    }

    public function getResponse(): Response {
        return $this->response;
    }

    public function getCurl(): CurlWrapper {
        return $this->curl;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getOptions(): array {
        return $this->options;
    }

    private function formatHeaders(): array {
        $headers = [];
        foreach($this->request->getHeaders() as $header => $value)
            $headers[] = "$header: $value";
        return $headers;
    }

    private function formatCookies(): string {
        $cookieString = '';
        $cookies = $this->request->getCookies();
        end($cookies);
        $lastCookie = key($cookies);
        foreach($cookies as $cookie => $value)
            $cookieString .= "$cookie=$value" . ($cookie == $lastCookie ? '' : '; ');
        return $cookieString;
    }

}