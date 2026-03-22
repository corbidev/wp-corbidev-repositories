<?php

namespace Corbidev\Repositories\Core;

use Corbidev\Repositories\Admin\Controllers\RepositoryController;
use Corbidev\Repositories\Admin\Controllers\RepositoryAdminController;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    public static function init(): void
    {
        if (is_multisite()) {
            add_action('network_admin_menu', [self::class, 'menus']);
        } else {
            add_action('admin_menu', [self::class, 'menus']);
        }

        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
    }

    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        /*
         |--------------------------------------------------------------------------
         | Menu principal → Repositories direct
         |--------------------------------------------------------------------------
         */

        add_menu_page(
            'Repositories',
            'Corbidev',
            $capability,
            'corbidev-repositories',
            [RepositoryAdminController::class, 'index'],
            'dashicons-database'
        );

        /*
         |--------------------------------------------------------------------------
         | Supprime le doublon WP auto
         |--------------------------------------------------------------------------
         */

        remove_submenu_page('corbidev-repositories', 'corbidev-repositories');

        /*
         |--------------------------------------------------------------------------
         | Plugins
         |--------------------------------------------------------------------------
         */

        add_submenu_page(
            'plugins.php',
            'Corbidev Plugins',
            'Corbidev',
            'install_plugins',
            'corbidev-plugins',
            function () {
                $_GET['type'] = 'plugin';
                RepositoryController::index();
            }
        );

        /*
         |--------------------------------------------------------------------------
         | Themes
         |--------------------------------------------------------------------------
         */

        add_submenu_page(
            'themes.php',
            'Corbidev Themes',
            'Corbidev',
            'install_themes',
            'corbidev-themes',
            function () {
                $_GET['type'] = 'theme';
                RepositoryController::index();
            }
        );
    }

    public static function enqueueAdminAssets(): void
    {
        /**
         * 🔥 FIX : charger UNIQUEMENT sur pages Corbidev
         * évite erreur heartbeat/wp-auth-check
         */
        if (!isset($_GET['page']) || !str_starts_with($_GET['page'], 'corbidev')) {
            return;
        }

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
