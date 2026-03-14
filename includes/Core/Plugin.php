<?php

namespace Corbidev\Repositories\Core;

use Corbidev\Repositories\Admin\Controllers\PluginsController;
use Corbidev\Repositories\Admin\Controllers\ThemesController;
use Corbidev\Repositories\Admin\Ajax\PluginAjax;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    public static function init(): void
    {
        /*
         |--------------------------------------------------------------------------
         | Menus admin / multisite
         |--------------------------------------------------------------------------
         */

        if (is_multisite()) {
            add_action('network_admin_menu', [self::class, 'menus']);
        } else {
            add_action('admin_menu', [self::class, 'menus']);
        }

        /*
         |--------------------------------------------------------------------------
         | AJAX
         |--------------------------------------------------------------------------
         */

        PluginAjax::init();

        /*
         |--------------------------------------------------------------------------
         | Admin assets
         |--------------------------------------------------------------------------
         */

        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
    }

    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        add_menu_page(
            'Corbidev',
            'Corbidev',
            $capability,
            'corbidev-repositories',
            [self::class, 'repositoriesPage'],
            'dashicons-database'
        );

        add_submenu_page(
            'plugins.php',
            'Corbidev Plugins',
            'Corbidev',
            $capability,
            'corbidev-plugins',
            [PluginsController::class, 'render']
        );

        add_submenu_page(
            'themes.php',
            'Corbidev Themes',
            'Corbidev',
            $capability,
            'corbidev-themes',
            [ThemesController::class, 'render']
        );
    }

    public static function repositoriesPage(): void
    {
        require CDR_PLUGIN_DIR . 'admin/pages/repositories.php';
    }

    public static function enqueueAdminAssets(): void
    {
        wp_enqueue_script(
            'corbidev-admin',
            CDR_PLUGIN_URL . 'admin/assets/js/corbidev.js',
            ['jquery'],
            '1.0',
            true
        );

        wp_localize_script(
            'corbidev-admin',
            'CorbidevAjax',
            [
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('corbidev_nonce')
            ]
        );
    }
}
