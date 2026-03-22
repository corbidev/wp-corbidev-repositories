<?php

namespace Corbidev\Repositories\Installer;

use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryInstaller
{
    public static function install(string $zip, string $repo, string $type = 'plugin')
    {
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/misc.php';

        Logger::info("Install $type: $repo");
        Logger::info("Download zip: $zip");

        ob_start();

        if ($type === 'theme') {
            include_once ABSPATH . 'wp-admin/includes/theme-install.php';
            $upgrader = new \Theme_Upgrader(new \Automatic_Upgrader_Skin());
            $baseDir = get_theme_root();
        } else {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());
            $baseDir = WP_PLUGIN_DIR;
        }

        $result = $upgrader->install($zip);

        ob_end_clean();

        Logger::info("Install raw result: " . var_export($result, true));

        if (is_wp_error($result)) {
            Logger::error("Install failed: " . $result->get_error_message());
            return false;
        }

        // 🔍 Recherche du dossier installé (avec version)
        $installedDir = null;

        if (is_dir($baseDir)) {
            $dirs = scandir($baseDir);

            foreach ($dirs as $dir) {
                if (strpos($dir, $repo . '-') === 0) {
                    $installedDir = $dir;
                    break;
                }
            }
        }

        if (!$installedDir) {
            Logger::error("Installed directory not found (with suffix)");
            return false;
        }

        $oldPath = $baseDir . '/' . $installedDir;
        $newPath = $baseDir . '/' . $repo;

        Logger::info("Post-install rename: $installedDir → $repo");

        // 🔥 Si dossier cible existe déjà → suppression
        if (is_dir($newPath)) {
            self::deleteDir($newPath);
        }

        if (!@rename($oldPath, $newPath)) {
            Logger::error("Rename failed: $oldPath → $newPath");
            return false;
        }

        Logger::info("Installed successfully: $repo");

        return true;
    }

    private static function deleteDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                self::deleteDir($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

    public static function isInstalled(string $repo, string $type = 'plugin'): bool
    {
        if ($type === 'theme') {
            return is_dir(get_theme_root() . '/' . $repo);
        }

        return is_dir(WP_PLUGIN_DIR . '/' . $repo);
    }

    public static function isActive(string $repo): bool
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        foreach ($plugins as $file => $plugin) {
            if (dirname($file) === $repo) {
                return is_plugin_active($file);
            }
        }

        return false;
    }

    public static function activate(string $repo)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $plugins = get_plugins();

        foreach ($plugins as $file => $plugin) {
            if (dirname($file) === $repo) {
                return activate_plugin($file);
            }
        }

        return false;
    }
}