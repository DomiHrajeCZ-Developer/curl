<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl;

use Nette\Http\Url;
use Nette\Http\UrlImmutable;
use Tracy\Debugger;
use ymastersk\Curl\Exception\LoggerException;
use ymastersk\Curl\Http\Request;
use ymastersk\Curl\Http\Settings;
use ymastersk\Curl\Tracy\CurlPanel;
use ymastersk\Curl\Tracy\Logger;

class CurlClient extends Settings {

    /** @var bool */
    private $loggerEnabled;

    /** @var Logger */
    private $logger;

    public function __construct(bool $loggerEnabled = true, array $options = [], array $headers = [], array $cookies = []){
        $this->loggerEnabled = $loggerEnabled;
        if($this->loggerEnabled){
            $this->logger = new Logger;
            Debugger::getBar()->addPanel(new CurlPanel($this));
        }
        $this->setUserAgent(Request::USERAGENT_DEFAULT);
        $this->setOptions(array_merge([
            'timeout' => 15,
            'maxRedirs' => 5,
            'followLocation' => false
        ], $options));
        $this->setHeaders($headers);
        $this->setCookies($cookies);
    }

    /** Returns array of cURL options for all requests. */
    public function getOptions(): array {
        return parent::getOptions();
    }

    /**
     * Sets cURL option for all requests. It's case insensitive.
     * You can setup or change every option that is listed at https://www.php.net/curl_setopt
     */
    public function setOption(string $option, $value): CurlClient {
        parent::setOption($option, $value);
        return $this;
    }

    /** Sets cURL options from array for all requests. */
    public function setOptions(array $options): CurlClient {
        parent::setOptions($options);
        return $this;
    }

    /** Returns array of cookies for all requests. */
    public function getCookies(): array {
        return parent::getCookies();
    }

    /** Sets cookie for all requests. */
    public function setCookie(string $name, $value): CurlClient {
        parent::setCookie($name, $value);
        return $this;
    }

    /** Sets cookies from array for all requests. */
    public function setCookies(array $cookies): CurlClient {
        parent::setCookies($cookies);
        return $this;
    }

    /** Returns array of HTTP headers for all requests. */
    public function getHeaders(): array {
        return parent::getHeaders();
    }

    /** Sets HTTP header for all requests. */
    public function setHeader(string $header, $value): CurlClient {
        parent::setHeader($header, $value);
        return $this;
    }

    /** Sets HTTP headers for all requests. */
    public function setHeaders(array $headers): CurlClient {
        parent::setHeaders($headers);
        return $this;
    }

    /** Sets User Agent for all requests. */
    public function setUserAgent(string $userAgent): CurlClient {
        return $this->setHeader('User-Agent', $userAgent);
    }

    /** Sets SSL version for all requests. */
    public function setSslVersion(int $version): CurlClient {
        parent::setSslVersion($version);
        return $this;
    }

    /** Sets SSL cipher list for all requests. */
    public function setSslCipherList(string $cipherList): CurlClient {
        parent::setSslCipherList($cipherList);
        return $this;
    }

    /** Sets if all certificates are trusted by default for all requests. */
    public function setCertificationVerify(bool $value = true): CurlClient {
        parent::setCertificationVerify($value);
        return $this;
    }

    /**
     * Sets path to the trusted certificate for all requests.
     *
     * Verify host values:
     *    0: Does not check the common name attribute.
     *    1: Checks that the common name attribute at least exists.
     *    2: Checks that the common name exists and that it matches the host name of the server.
     */
    public function setTrustedCertificate(string $certificate, int $verify = Request::VERIFY_MATCH): CurlClient {
        parent::setTrustedCertificate($certificate, $verify);
        return $this;
    }

    /**
     * Sets path to directory which contains trusted certificates for all requests.
     *
     * Verify host values:
     *    0: Does not check the common name attribute.
     *    1: Checks that the common name attribute at least exists.
     *    2: Checks that the common name exists and that it matches the host name of the server.
     */
    public function setTrustedCertificatesDirectory(string $directory, int $verify = Request::VERIFY_MATCH): CurlClient {
        parent::setTrustedCertificatesDirectory($directory, $verify);
        return $this;
    }

    /**
     * Creates request.
     * @param string|Url|UrlImmutable $url
     */
    public function request(string $method, $url, array $params = []): Request {
        return new Request($this, $method, $url, $params, $this->getOptions(), $this->getHeaders(), $this->getCookies());
    }

    /**
     * Creates GET request.
     * @param string|Url|UrlImmutable $url 
     */
    public function get($url): Request {
        return $this->request(Request::GET, $url);
    }

    /**
     * Creates POST request.
     * @param string|Url|UrlImmutable $url
     */
    public function post($url, array $params = []): Request {
        return $this->request(Request::POST, $url, $params);
    }

    /**
     * Creates HEAD request.
     * @param string|Url|UrlImmutable $url 
     */
    public function head($url): Request {
        return $this->request(Request::HEAD, $url);
    }

    /**
     * Creates PUT request.
     * @param string|Url|UrlImmutable $url
     */
    public function put($url, array $params = []): Request {
        return $this->request(Request::PUT, $url, $params);
    }

    /**
     * Creates PATCH request.
     * @param string|Url|UrlImmutable $url
     */
    public function patch($url, array $params = []): Request {
        return $this->request(Request::PATCH, $url, $params);
    }

    /**
     * Creates DELETE request.
     * @param string|Url|UrlImmutable $url
     */
    public function delete($url, array $params = []): Request {
        return $this->request(Request::DELETE, $url, $params);
    }

    public function isLoggerEnabled(): bool {
        return $this->loggerEnabled;
    }

    public function getLogger(): Logger {
        if(!$this->isLoggerEnabled())
            throw new LoggerException('Logger is not enabled.');
        return $this->logger;
    }

}