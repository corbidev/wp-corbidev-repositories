<?php

namespace Corbidev\Repositories\Installer;

use Corbidev\Repositories\Github\GithubClient;
use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class ThemeInstaller
{
    public static function install(string $repo)
    {
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        Logger::info("Install theme: $repo");

        $version = self::getLatestTag($repo);

        if (!$version) {

            Logger::error("No tag found for theme: $repo");

            return false;
        }

        $zip = "https://github.com/corbidev/$repo/archive/refs/tags/$version.zip";

        Logger::info("Download zip: $zip");

        $upgrader = new \Theme_Upgrader();

        $result = $upgrader->install($zip);

        Logger::info("Install result: " . var_export($result, true));

        return $result;
    }

    private static function getLatestTag(string $repo): ?string
    {
        Logger::debug("Fetching tags for theme $repo");

        $tags = GithubClient::get("/repos/corbidev/$repo/tags");

        if (!$tags) {
            return null;
        }

        return $tags[0]['name'] ?? null;
    }
}
