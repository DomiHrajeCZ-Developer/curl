<?php

/*
 * This file is part of the cURL Library.
 * By ymastersk (https://ymastersk.net).
 */

declare(strict_types=1);

namespace ymastersk\Curl\Http;

use Nette\Utils\Strings;

class ResponseFactory {

    public static function create($body, $info, array $headers): Response {
        return new Response(
            $body,
            $info['http_code'],
            $info['http_version'],
            Strings::lower($info['scheme']),
            round($info['total_time'] * 1000, 1),
            self::formatHeaders($info['request_header'] ?? null),
            $headers
        );
    }

    private static function formatHeaders($raw): array {
        $headers = [];
        if($raw){
            $matches = Strings::matchAll($raw, '~(?P<header>.*?)\:\s(?P<value>.*)~');
            foreach($matches as $match)
                $headers[Strings::lower($match['header'])] = trim($match['value']);
        }
        return $headers;
    }

}