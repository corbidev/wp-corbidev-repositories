<?php

namespace Corbidev\Repositories\Manager;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryDelete
{
    public function deletePlugin(string $pluginFile): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';

        if (is_plugin_active($pluginFile)) {
            deactivate_plugins($pluginFile);
        }

        $result = delete_plugins([$pluginFile]);

        return !is_wp_error($result);
    }

    public function deleteTheme(string $themeSlug): bool
    {
        require_once ABSPATH . 'wp-admin/includes/theme.php';

        if (get_stylesheet() === $themeSlug) {
            return false;
        }

        $result = delete_theme($themeSlug);

        return !is_wp_error($result);
    }
}