<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Admin Menu Registration
|--------------------------------------------------------------------------
*/

if (is_multisite()) {

    add_action('network_admin_menu', function (): void {

        add_menu_page(
            __('Repositories', 'corbidevrepositories'),
            __('Repositories', 'corbidevrepositories'),
            'manage_network_options',
            'corbidev-repositories',
            function (): void {
                require CDR_PATH . 'admin/pages/repositories-page.php';
            },
            'dashicons-database',
            58
        );

        add_submenu_page(
            'plugins.php',
            __('Repository Extensions', 'corbidevrepositories'),
            __('Repository Extensions', 'corbidevrepositories'),
            'manage_network_options',
            'corbidev-repositories-extensions',
            function (): void {
                require CDR_PATH . 'admin/pages/extensions-page.php';
            }
        );

        add_submenu_page(
            'themes.php',
            __('Repository Themes', 'corbidevrepositories'),
            __('Repository Themes', 'corbidevrepositories'),
            'manage_network_options',
            'corbidev-repositories-themes',
            function (): void {
                require CDR_PATH . 'admin/pages/themes-page.php';
            }
        );
    });

} else {

    add_action('admin_menu', function (): void {

        add_menu_page(
            __('Repositories', 'corbidevrepositories'),
            __('Repositories', 'corbidevrepositories'),
            'manage_options',
            'corbidev-repositories',
            function (): void {
                require CDR_PATH . 'admin/pages/repositories-page.php';
            },
            'dashicons-database',
            58
        );
    });
}

/*
|--------------------------------------------------------------------------
| Admin Assets via Vite Manifest
|--------------------------------------------------------------------------
*/

add_action('admin_enqueue_scripts', function (): void {

    if (is_multisite() && !is_network_admin()) {
        return;
    }

    $manifestPath = CDR_PATH . 'assets/dist/manifest.json';

    if (!file_exists($manifestPath)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    if (!is_array($manifest)) {
        return;
    }

    foreach ($manifest as $entry) {

        if (isset($entry['file']) && str_ends_with($entry['file'], '.js')) {

            wp_enqueue_script(
                'corbidev-repositories-admin',
                CDR_URL . 'assets/dist/' . $entry['file'],
                [],
                CDR_VERSION,
                true
            );
        }

        if (isset($entry['css']) && is_array($entry['css'])) {

            foreach ($entry['css'] as $cssFile) {

                wp_enqueue_style(
                    'corbidev-repositories-admin',
                    CDR_URL . 'assets/dist/' . $cssFile,
                    [],
                    CDR_VERSION
                );
            }
        }
    }
});
