<?php

namespace Corbidev\Repositories\Cache;

if (!defined('ABSPATH')) {
    exit;
}

class GithubCache
{
    const TTL = 3600;

    public static function get(string $key)
    {
        return get_site_transient('corbidev_github_' . $key);
    }

    public static function set(string $key, $value): void
    {
        set_site_transient('corbidev_github_' . $key, $value, self::TTL);
    }

    public static function delete(string $key): void
    {
        delete_site_transient('corbidev_github_' . $key);
    }
}
