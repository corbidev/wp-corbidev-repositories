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

        $result = activate_plugin($pluginFile);

        return !is_wp_error($result);
    }

    public static function deactivate(string $pluginFile): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        deactivate_plugins($pluginFile);

        return true;
    }
}