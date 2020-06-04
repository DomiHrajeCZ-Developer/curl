<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use ymastersk\Curl\CurlClient;

final class CurlExtension extends CompilerExtension {

    public function getConfigSchema(): Schema {
        return Expect::structure([
            'debugger' => Expect::bool()->default(true),
            'options' => Expect::array(),
            'headers' => Expect::array(),
            'cookies' => Expect::array()
        ]);
    }

    public function loadConfiguration(): void {
        $config = (array) $this->getConfig();
        $this->getContainerBuilder()->addDefinition($this->prefix('client'))->setFactory(CurlClient::class, [$config['debugger'], $config['options'], $config['headers'], $config['cookies']]);
    }

}