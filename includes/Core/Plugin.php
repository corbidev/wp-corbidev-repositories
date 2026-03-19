<?php

namespace Corbidev\Repositories\Core;

use Corbidev\Repositories\Admin\Controllers\RepositoryController;
use Corbidev\Repositories\Ajax\RepositoryAjax;

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
         | AJAX (générique)
         |--------------------------------------------------------------------------
         */

        RepositoryAjax::register();
    }

    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        /*
         |--------------------------------------------------------------------------
         | Menu principal (optionnel)
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
            'dashicons-database'
        );

        /*
         |--------------------------------------------------------------------------
         | Menu dans Extensions (plugins)
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
         | Menu dans Apparence (thèmes)
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
