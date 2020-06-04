<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Tracy;

use ymastersk\Curl\Sender\CurlSender;

class Entity {

    /** @var string */
    private $method;
    
    /** @var float */
    private $time;
    
    /** @var string */
    private $url;
    
    /** @var int */
    private $code;
    
    /** @var array */
    private $params;

    /** @var array */
    private $options;

    public function __construct(string $method, float $time, string $url, int $code, array $params = [], array $options = []){
        $this->method = $method;
        $this->time = $time;
        $this->url = $url;
        $this->code = $code;
        $this->params = $params;
        $this->options = $options;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getTime(): float {
        return $this->time;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function getParams(): array {
        return $this->params;
    }

    public function getOptions(): array {
        return $this->options;
    }

    public static function create(CurlSender $curlSender): Entity {
        return new static($curlSender->getRequest()->getMethod(), $curlSender->getResponse()->getTime(), $curlSender->getRequest()->getUrl()->getAbsoluteUrl(), $curlSender->getResponse()->getCode(), $curlSender->getRequest()->getParams(), $curlSender->getOptions());
    }

}