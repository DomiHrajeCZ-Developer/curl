<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Tracy;

use ymastersk\Curl\Sender\CurlSender;

class Logger {

    /** @var array|Entity[] */
    private $entities = [];

    public function log(CurlSender $curlSender){
        $this->entities[] = Entity::create($curlSender);
    }

    public function getEntities(): array {
        return $this->entities;
    }

}