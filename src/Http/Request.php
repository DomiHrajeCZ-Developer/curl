<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Http;

use Nette\Http\IRequest;
use Nette\Http\Url;
use ymastersk\Curl\CurlClient;
use ymastersk\Curl\Sender\CurlSender;

class Request extends Settings {

    public const
		GET = IRequest::GET,
		POST = IRequest::POST,
		HEAD = IRequest::HEAD,
		PUT = IRequest::PUT,
		DELETE = IRequest::DELETE,
        PATCH = IRequest::PATCH;

    public const
        USERAGENT_DEFAULT = 'ymastersk/curl (compatible; +https://ymastersk.net)',
        USERAGENT_CHROME = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36',
        USERAGENT_OPERA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36 OPR/68.0.3618.125',
        USERAGENT_FIREFOX = 'Mozilla/5.0 (X11; Linux i686; rv:76.0) Gecko/20100101 Firefox/76.0',
        USERAGENT_GOOGLEBOT = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
    
    public const
        VERIFY_NO = 0, // Does not check the common name attribute.
        VERIFY_COMMON = 1, // Checks that the common name attribute at least exists.
        VERIFY_MATCH = 2; // Checks that the common name exists and that it matches the host name of the server.

    /** @var CurlClient */
    private $client;
        
    /** @var string */
    private $method;

    /** @var Url */
    private $url;

    /** @var array */
    private $params;

    /**
     * @param string|Url|UrlImmutable $url
     */
    public function __construct(CurlClient $client, string $method = self::GET, $url, array $params, array $options, array $headers, array $cookies){
        $this->client = $client;
        $this->method = $method;
        $this->setUrl($url);
        $this->params = $params;
        $this->setOptions($options);
        $this->setHeaders($headers);
        $this->setCookies($cookies);
    }

    public function getClient(): CurlClient {
        return $this->client;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function isMethod(string $method): bool {
        return $this->getMethod() === $method;
    }

    public function getUrl(): Url {
        return $this->url;
    }

    public function setUrl($url): Request {
        if($url instanceof Url)
            $this->url = $url;
        else
            $this->url = new Url($url);
        return $this;
    }

    /** Returns array of parameters. */
    public function getParams(): array {
        return $this->params;
    }

    /** Returns array of HTTP headers for this request. */
    public function getHeaders(): array {
        return parent::getHeaders();
    }

    /** Sets HTTP header for this request. */
    public function setHeader(string $header, $value): Request {
        parent::setHeader($header, $value);
        return $this;
    }

    /** Sets HTTP headers for this request. */
    public function setHeaders(array $headers): Request {
        parent::setHeaders($headers);
        return $this;
    }

    /** Sets User Agent for this request. */
    public function setUserAgent(string $userAgent): Request {
        $this->setHeader('User-Agent', $userAgent);
        return $this;
    }

    /** Returns array of cURL options for this request. */
    public function getOptions(): array {
        return parent::getOptions();
    }

    /** 
     * Sets cURL option for this request. It's case insensitive.
     * You can setup or change every option that is listed at https://www.php.net/curl_setopt
     */
    public function setOption(string $option, $value): Request {
        parent::setOption($option, $value);
        return $this;
    }

    /** Sets cURL options for this request from array. */
    public function setOptions(array $options): Request {
        parent::setOptions($options);
        return $this;
    }

    /** Returns array of cookies for this request. */
    public function getCookies(): array {
        return parent::getCookies();
    }

    /** Sets cookie for this request. */
    public function setCookie(string $name, $value): Request {
        parent::setCookie($name, $value);
        return $this;
    }

    /** Sets cookies from array for this request. */
    public function setCookies(array $cookies): Request {
        parent::setCookies($cookies);
        return $this;
    }

    /** Sets SSL version for this request. */
    public function setSslVersion(int $version): Request {
        parent::setSslVersion($version);
        return $this;
    }

    /** Sets SSL cipher list for this request. */
    public function setSslCipherList(string $cipherList): Request {
        parent::setSslCipherList($cipherList);
        return $this;
    }

    /** Sets if all certificates are trusted by default for this request. */
    public function setCertificationVerify(bool $value = true): Request {
        parent::setCertificationVerify($value);
        return $this;
    }

    /** 
     * Sets path to the trusted certificate for this request.
     * 
     * Verify host values:
     *    0: Does not check the common name attribute.
     *    1: Checks that the common name attribute at least exists.
     *    2: Checks that the common name exists and that it matches the host name of the server.
     */
    public function setTrustedCertificate(string $certificate, int $verify = Request::VERIFY_MATCH): Request {
        parent::setTrustedCertificate($certificate, $verify);
        return $this;
    }

    /**
     * Sets path to directory which contains trusted certificates for this request.
     * 
     * Verify host values:
     *    0: Does not check the common name attribute.
     *    1: Checks that the common name attribute at least exists.
     *    2: Checks that the common name exists and that it matches the host name of the server.
     */
    public function setTrustedCertificatesDirectory(string $directory, int $verify = Request::VERIFY_MATCH): Request {
        parent::setTrustedCertificatesDirectory($directory, $verify);
        return $this;
    }

    /** Sends request and returns instance of ymastersk\Curl\Http\Response. */
    public function send(): Response {
        $curlSender = new CurlSender($this);
        return $curlSender->makeRequest();
    }

}