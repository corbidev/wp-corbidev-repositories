<?php

namespace Corbidev\Repositories\Github;

use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class GithubClient
{
    private static string $base = 'https://api.github.com';

    public static function get(string $endpoint)
    {
        Logger::debug("GitHub request: " . $endpoint);

        $cached = GithubCache::get($endpoint);

        if ($cached !== null) {
            Logger::debug("GitHub cache hit");
            return $cached;
        }

        $url = self::$base . $endpoint;

        $response = wp_remote_get($url, [
            'headers' => [
                'User-Agent' => 'CorbidevRepositories'
            ]
        ]);

        if (is_wp_error($response)) {

            Logger::error($response->get_error_message());

            return null;
        }

        $body = wp_remote_retrieve_body($response);

        $data = json_decode($body, true);

        GithubCache::set($endpoint, $data);

        Logger::debug("GitHub cache store");

        return $data;
    }
}
