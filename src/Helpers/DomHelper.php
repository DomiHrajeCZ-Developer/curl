<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Helpers;

use DOMDocument;

class DomHelper {

    /** @var DOMDocument */
    private $dom;

    private $body;

    public function __construct($body){
        $this->body = $body;
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->resolveExternals = false;
        $this->dom->validateOnParse = false;
        $this->dom->preserveWhiteSpace = false;
        $this->dom->strictErrorChecking = false;
        $this->dom->recover = true;
        @$this->dom->loadHTML($this->body);
    }

    public function getDomDocument(): DOMDocument {
        return $this->dom;
    }

}