<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Wrapper;

class CurlWrapper {

    /** @var resource */
    private $curl;

    public function __construct(string $url){
        $this->curl = curl_init($url);
    }

    public function setOption(int $option, $value): void {
        curl_setopt($this->curl, $option, $value);
    }

    /** @return string|bool */
    public function getBody(){
        return curl_exec($this->curl);
    }

    public function getInfo(){
        return curl_getinfo($this->curl);
    }

    public function close(): void {
        curl_close($this->curl);
    }

}