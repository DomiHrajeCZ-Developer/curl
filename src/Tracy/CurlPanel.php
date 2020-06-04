<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Tracy;

use Tracy\Helpers;
use Tracy\IBarPanel;
use ymastersk\Curl\CurlClient;

class CurlPanel implements IBarPanel {

    /** @var CurlClient */
    private $client;

    public function __construct(CurlClient $client){
        $this->client = $client;
    }

    public function getTab(): string {
        return Helpers::capture(function(){
            require __DIR__ . '/panels/log.tab.phtml';
        });
    }

    public function getPanel(): string {
        return Helpers::capture(function(){
            $entities = $this->client->getLogger()->getEntities();
            require __DIR__ . '/panels/log.panel.phtml';
        });
    }

}