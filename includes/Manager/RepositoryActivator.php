<?php

namespace Corbidev\Repositories\Manager;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryActivator
{
    public static function activate(string $pluginFile): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $result = is_multisite()
            ? activate_plugin($pluginFile, '', true)
            : activate_plugin($pluginFile);

        return !is_wp_error($result);
    }

    public static function deactivate(string $pluginFile): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (is_multisite()) {
            deactivate_plugins($pluginFile, false, true);
        } else {
            deactivate_plugins($pluginFile);
        }

        return true;
    }
}
