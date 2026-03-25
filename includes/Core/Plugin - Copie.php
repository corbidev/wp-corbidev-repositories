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
        // 🔥 Charger les traductions PHP
        add_action('init', [self::class, 'loadTextDomain']);

        if (is_multisite()) {
            add_action('network_admin_menu', [self::class, 'menus']);
        } else {
            add_action('admin_menu', [self::class, 'menus']);
        }

        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminAssets']);
    }

    /**
     * 🔥 Load plugin textdomain (PHP translations)
     */
    public static function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'corbidev',
            false,
            dirname(plugin_basename(CDR_PLUGIN_FILE)) . '/languages'
        );
    }

    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        add_menu_page(
            __('Repositories', 'corbidev'),
            'Corbidev',
            $capability,
            'corbidev-repositories',
            [RepositoryAdminController::class, 'index'],
            'dashicons-database'
        );

        remove_submenu_page('corbidev-repositories', 'corbidev-repositories');

        add_submenu_page(
            'plugins.php',
            __('Corbidev Plugins', 'corbidev'),
            'Corbidev',
            'install_plugins',
            'corbidev-plugins',
            function () {
                $_GET['type'] = 'plugin';
                RepositoryController::index();
            }
        );

        add_submenu_page(
            'themes.php',
            __('Corbidev Themes', 'corbidev'),
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
        // Charger uniquement sur pages Corbidev
        if (!isset($_GET['page']) || !str_starts_with($_GET['page'], 'corbidev')) {
            return;
        }

        /**
         * 🔥 Build Vite
         */
        $script_path = CDR_PLUGIN_DIR . 'assets/dist/admin.js';
        $script_url  = CDR_PLUGIN_URL . 'assets/dist/admin.js';

        // Version dynamique (cache bust)
        $version = file_exists($script_path) ? filemtime($script_path) : '1.0';

        wp_enqueue_script(
            'corbidev-admin',
            $script_url,
            ['wp-i18n'], // 🔥 obligatoire pour __()
            $version,
            true
        );

        /**
         * 🔥 Traductions JS
         */
        wp_set_script_translations(
            'corbidev-admin',
            'corbidev',
            CDR_PLUGIN_DIR . 'languages'
        );

        /**
         * 🔥 AJAX
         */
        wp_localize_script(
            'corbidev-admin',
            'cdr_ajax',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('corbidev_nonce'),
            ]
        );
    }
}
