<?php

namespace Corbidev\Repositories\Installer;

use Corbidev\Repositories\Github\GithubClient;
use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class PluginInstaller
{
    public static function install(string $repo)
    {
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        Logger::info("Install plugin: $repo");

        $version = self::getLatestTag($repo);

        if (!$version) {
            Logger::error("No tag found for plugin: $repo");
            return false;
        }

        $zip = "https://github.com/corbidev/$repo/archive/refs/tags/$version.zip";

        Logger::info("Download zip: $zip");

        $upgrader = new \Plugin_Upgrader();

        $result = $upgrader->install($zip);

        Logger::info("Install raw result: " . var_export($result, true));

        // 🔥 FIX GitHub : dossier versionné
        $pluginDir = WP_PLUGIN_DIR . '/' . $repo;
        $extractedDir = WP_PLUGIN_DIR . '/' . $repo . '-' . ltrim($version, 'vV');

        if (!is_dir($pluginDir) && is_dir($extractedDir)) {
            Logger::info("Renaming $extractedDir → $pluginDir");
            rename($extractedDir, $pluginDir);
        }

        if (!is_dir($pluginDir)) {
            Logger::error("Plugin directory not found after install");
            return false;
        }

        Logger::info("Plugin installed successfully");

        return true;
    }

    private static function getLatestTag(string $repo): ?string
    {
        Logger::debug("Fetching tags for $repo");

        $tags = GithubClient::get("/repos/corbidev/$repo/tags");

        if (!$tags) {
            Logger::error("GitHub tags empty");
            return null;
        }

        return $tags[0]['name'] ?? null;
    }

    public static function isInstalled(string $repo): bool
    {
        return is_dir(WP_PLUGIN_DIR . '/' . $repo);
    }

    public static function isActive(string $repo): bool
    {
        $pluginFile = self::getPluginFile($repo);

        if (!$pluginFile) {
            return false;
        }

        return is_plugin_active($pluginFile);
    }

    public static function getPluginFile(string $repo): ?string
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        foreach ($plugins as $file => $plugin) {
            if (dirname($file) === $repo) {
                return $file;
            }
        }

        return null;
    }

    public static function activate(string $pluginFile)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        return activate_plugin($pluginFile);
    }
}
