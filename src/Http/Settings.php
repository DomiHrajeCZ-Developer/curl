<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Http;

use Nette\Utils\Strings;
use ymastersk\Curl\Exception\CertificateException;
use ymastersk\Curl\Exception\InvalidOptionException;

abstract class Settings {

    /** @var array */
    protected $options = [];

    /** @var array */
    protected $headers = [];

    /** @var array */
    protected $cookies = [];

    protected function getHeaders(): array {
        return $this->headers;
    }

    protected function setHeader(string $header, $value){
        $this->headers[$header] = $value;
    }

    protected function setHeaders(array $headers){
        $this->headers = array_merge($this->headers, $headers);
    }

    protected function setUserAgent(string $userAgent){
        $this->setHeader('User-Agent', $userAgent);
    }

    protected function getOptions(): array {
        return $this->options;
    }

    protected function setOption(string $option, $value){
        $opt = 'CURLOPT_' . Strings::upper($option);
        if(!defined($opt))
            throw new InvalidOptionException("Option $opt does not exist.");
        $this->options[$option] = $value;
    }

    protected function setOptions(array $options){
        $this->options = array_merge($this->options, $options);
    }

    protected function getCookies(): array {
        return $this->cookies;
    }

    protected function setCookie(string $name, $value){
        $this->cookies[$name] = $value;
    }

    protected function setCookies(array $cookies){
        $this->cookies = array_merge($this->cookies, $cookies);
    }

    protected function setSslVersion(int $version){
        $this->setOption('sslVersion', $version);
    }

    protected function setSslCipherList(string $cipherList){
        $this->setOption('ssl_cipher_list', $cipherList);
    }

    protected function setCertificationVerify(bool $value = true){
        $this->setOption('ssl_verifyPeer', $value);
    }

    protected function setTrustedCertificate(string $certificate, int $verify = Request::VERIFY_MATCH){
        if(!file_exists($certificate))
            throw new CertificateException("Missing certificate $certificate.");
        $this->checkVerify($verify);
        $this->setCertificationVerify();
        $this->removeOption('caPath');
        $this->setOption('ssl_verifyHost', $verify);
        $this->setOption('caInfo', $certificate);
    }

    protected function setTrustedCertificatesDirectory(string $directory, int $verify = Request::VERIFY_MATCH){
        if(!is_dir($directory))
            throw new CertificateException("Missing certificate directory $directory");
        $this->checkVerify($verify);
        $this->setCertificationVerify();
        $this->removeOption('caInfo');
        $this->setOption('ssl_verifyHost', $verify);
        $this->setOption('caPath', $directory);
    }

    private function checkVerify(int $verify){
        if(!in_array($verify, [0, 1, 2]))
            throw new CertificateException('Invalid verification type, type must be 0, 1 or 2.');
    }

    private function removeOption(string $option){
        unset($this->options[$option]);
    }

}