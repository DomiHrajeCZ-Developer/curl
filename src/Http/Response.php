<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Http;

use Nette\Utils\Strings;

class Response {

    /** @var ResponseBody */
    private $body;

    /** @var int */
    private $code;

    /** @var int */
    private $version;

    /** @var string */
    private $scheme;

    /** @var float */
    private $time;

    /** @var array */
    private $requestHeaders = [];

    /** @var array */
    private $responseHeaders = [];

    /** @param string|bool $body */
    public function __construct($body, int $code, int $version, string $scheme, float $time, array $requestHeaders, array $responseHeaders){
        $this->body = new ResponseBody($body);
        $this->code = $code;
        $this->version = $version;
        $this->scheme = $scheme;
        $this->time = $time;
        $this->requestHeaders = $requestHeaders;
        $this->responseHeaders = $responseHeaders;
    }

    /** Returns instance of ymastersk\Curl\Http\ResponseBody or raw response body. */
    public function getBody(): ResponseBody {
        return $this->body;
    }

    /** Returns HTTP status code. */
    public function getCode(): int {
        return $this->code;
    }

    /** Returns the version used in the HTTP connection. */
    public function getVersion(): int {
        return $this->version;
    }

    /** Returns the URL scheme used for the request. */
    public function getScheme(): string {
        return $this->scheme;
    }

    /** Returns total transaction time in milliseconds for transfer. */
    public function getTime(): float {
        return $this->time;
    }

    /** Returns array of response HTTP headers. */
    public function getHeaders(): array {
        return $this->responseHeaders;
    }

    /** Returns value of response header. If not exists returns null. */
    public function getHeader(string $name){
        return $this->headers[Strings::lower($name)] ?? null;
    }

    /** Returns array of request HTTP headers. */
    public function getRequestHeaders(): array {
        return $this->requestHeaders;
    }

    /** Returns value of request header. If not exists returns null. */
    public function getRequestHeader(string $name){
        return $this->requestHeaders[Strings::lower($name)] ?? null;
    }

}