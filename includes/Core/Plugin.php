<?php

namespace Corbidev\Repositories\Core;

use Corbidev\Repositories\Admin\Controllers\RepositoryController;
use Corbidev\Repositories\Admin\Controllers\RepositoryAdminController;
use Corbidev\Repositories\Updates\WordPressUpdateBridge;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private const ADMIN_HANDLE = 'corbidev-admin-js';
    private const UI_BRIDGE_HANDLE = 'corbidev-ui-bridge-js';
    private const FRONT_HANDLE = 'corbidev-app-js';

    public static function init(): void
    {
        add_action('init', [self::class, 'loadTextDomain']);

        if (is_admin()) {
            WordPressUpdateBridge::register();
        }

        if (is_multisite()) {
            add_action('network_admin_menu', [self::class, 'menus']);
        } else {
            add_action('admin_menu', [self::class, 'menus']);
        }

        add_action('admin_enqueue_scripts', [self::class, 'enqueueAdmin']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueFront']);
    }

    public static function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'corbidevrepositories',
            false,
            dirname(plugin_basename(CDR_PLUGIN_FILE)) . '/languages'
        );
    }

    public static function menus(): void
    {
        $capability = is_multisite() ? 'manage_network_options' : 'manage_options';

        add_menu_page(
            __('Repositories', 'corbidevrepositories'),
            'Corbidev',
            $capability,
            'corbidev-repositories',
            [RepositoryAdminController::class, 'index'],
            'dashicons-database'
        );

        add_submenu_page(
            'corbidev-repositories',
            __('Settings', 'corbidevrepositories'),
            __('Settings', 'corbidevrepositories'),
            $capability,
            'corbidev-repositories',
            [RepositoryAdminController::class, 'index']
        );

        add_submenu_page(
            'corbidev-repositories',
            __('Info', 'corbidevrepositories'),
            __('Info', 'corbidevrepositories'),
            $capability,
            'corbidev-info',
            [self::class, 'renderInfoPage']
        );

        add_submenu_page(
            'plugins.php',
            __('Corbidev Plugins', 'corbidevrepositories'),
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
            __('Corbidev Themes', 'corbidevrepositories'),
            'Corbidev',
            'install_themes',
            'corbidev-themes',
            function () {
                $_GET['type'] = 'theme';
                RepositoryController::index();
            }
        );
    }

    public static function renderInfoPage(): void
    {
        $file = CDR_PLUGIN_DIR . 'admin/pages/info.php';

        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * =========================
     * ADMIN
     * =========================
     */
    public static function enqueueAdmin(): void
    {
        if (!isset($_GET['page']) || !str_starts_with($_GET['page'], 'corbidev')) {
            return;
        }

        self::enqueueUiBridge();
        self::enqueueEntry('assets/src/admin/main.js', self::ADMIN_HANDLE, true);
    }

    /**
     * =========================
     * FRONT
     * =========================
     */
    public static function enqueueFront(): void
    {
        self::enqueueEntry('assets/src/main.js', self::FRONT_HANDLE, false);
    }

    /**
     * =========================
     * UI BRIDGE (ADMIN)
     * =========================
     */
    private static function enqueueUiBridge(): void
    {
        self::enqueueEntry('assets/src/ui-bridge/main.tsx', self::UI_BRIDGE_HANDLE, false);
    }

    /**
     * =========================
     * GENERIC ENQUEUE (VITE)
     * =========================
     */
    private static function enqueueEntry(string $key, string $handle, bool $with_ajax = false): void
    {
        $manifest = corbidev_get_manifest();
        $entry = $manifest[$key] ?? null;

        if (!$entry || empty($entry['file'])) {
            return;
        }

        $base_url = CDR_PLUGIN_URL . 'assets/dist/';

        /**
         * CSS
         */
        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $index => $css) {
                wp_enqueue_style(
                    $handle . '-css-' . $index,
                    $base_url . $css,
                    [],
                    CDR_VERSION
                );
            }
        }

        /**
         * JS
         */
        wp_enqueue_script('wp-i18n');

        wp_enqueue_script(
            $handle,
            $base_url . $entry['file'],
            ['wp-i18n'],
            CDR_VERSION,
            true
        );

        wp_set_script_translations(
            $handle,
            'corbidevrepositories',
            CDR_PLUGIN_DIR . 'languages'
        );

        /**
         * AJAX (admin uniquement)
         */
        if ($with_ajax) {
            wp_localize_script($handle, 'cdr_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('corbidev_nonce'),
            ]);
        }

        /**
         * 🔥 imports Vite (chunks)
         */
        if (!empty($entry['imports'])) {
            foreach ($entry['imports'] as $import) {

                if (!isset($manifest[$import]['file'])) {
                    continue;
                }

                wp_enqueue_script(
                    $handle . '-chunk-' . md5($import),
                    $base_url . $manifest[$import]['file'],
                    [],
                    CDR_VERSION,
                    true
                );
            }
        }
    }
}
