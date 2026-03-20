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
    }

    /**
     * Enregistre les menus
     */
    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        /*
         |--------------------------------------------------------------------------
         | Menu principal
         |--------------------------------------------------------------------------
         */

        add_menu_page(
            'Corbidev',
            'Corbidev',
            $capability,
            'corbidev-repositories',
            function () {
                $_GET['type'] = 'plugin';
                RepositoryController::index();
            },
            'dashicons-database',
            58
        );

        /*
         |--------------------------------------------------------------------------
         | Sous-menu : gestion des dépôts (CRUD)
         |--------------------------------------------------------------------------
         */

        add_submenu_page(
            'corbidev-repositories',
            'Repositories',
            'Repositories',
            $capability,
            'corbidev-repo-manager',
            [RepositoryAdminController::class, 'index']
        );

        /*
         |--------------------------------------------------------------------------
         | Menu dans Extensions (Plugins)
         |--------------------------------------------------------------------------
         */

        add_submenu_page(
            'plugins.php',
            'Corbidev Plugins',
            'Corbidev',
            $capability,
            'corbidev-plugins',
            function () {
                $_GET['type'] = 'plugin';
                RepositoryController::index();
            }
        );

        /*
         |--------------------------------------------------------------------------
         | Menu dans Apparence (Thèmes)
         |--------------------------------------------------------------------------
         */

        add_submenu_page(
            'themes.php',
            'Corbidev Themes',
            'Corbidev',
            $capability,
            'corbidev-themes',
            function () {
                $_GET['type'] = 'theme';
                RepositoryController::index();
            }
        );
    }
}
