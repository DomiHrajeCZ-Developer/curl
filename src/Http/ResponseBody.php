<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Http;

use DOMDocument;
use Nette\Utils\Json;
use ymastersk\Curl\Helpers\DomHelper;

class ResponseBody {

    private $body;

    public function __construct($body){
        $this->body = $body;
    }

    public function __toString(){
        return $this->body;
    }

    /** Decodes a JSON string. Accepts flag Nette\Utils\Json::FORCE_ARRAY. */
    public function decodeJson(int $flags = 0){
        return Json::decode($this->body, $flags);
    }

    /** Loads HTML from response body and returns DOMDocument instance. */
    public function toDom(): DOMDocument {
        $helper = new DomHelper($this->body);
        return $helper->getDomDocument();
    }

}