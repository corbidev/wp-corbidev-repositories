<?php
if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Autoload
|--------------------------------------------------------------------------
*/
require_once CDR_PLUGIN_DIR . 'includes/autoload.php';

/*
|--------------------------------------------------------------------------
| Init Plugin
|--------------------------------------------------------------------------
*/
add_action('plugins_loaded', function () {
    \Corbidev\Repositories\Core\Plugin::init();
});

/*
|--------------------------------------------------------------------------
| Ajax (FRONT + INSTALL)
|--------------------------------------------------------------------------
*/
add_action('plugins_loaded', function () {
    \Corbidev\Repositories\Ajax\RepositoryAjax::register();
});

/*
|--------------------------------------------------------------------------
| Ajax ADMIN (CRUD REPOSITORIES)
|--------------------------------------------------------------------------
*/
add_action('plugins_loaded', function () {
    \Corbidev\Repositories\Ajax\RepositoryAdminAjax::register();
});

/*
|--------------------------------------------------------------------------
| Admin Assets via Vite Manifest
|--------------------------------------------------------------------------
*/
add_action('admin_enqueue_scripts', function (): void {

    if (!is_admin()) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (!$screen) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Pages autorisées
    |--------------------------------------------------------------------------
    */
    $allowed = [
        'toplevel_page_corbidev-repositories',
        'plugins',
        'themes',
    ];

    $isCorbidevPage = str_contains($screen->id, 'corbidev');

    if (!$isCorbidevPage && !in_array($screen->id, $allowed)) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Manifest
    |--------------------------------------------------------------------------
    */
    $manifestPath = CDR_PLUGIN_DIR . 'assets/dist/.vite/manifest.json';

    if (!file_exists($manifestPath)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    if (!isset($manifest['assets/src/admin/main.js'])) {
        return;
    }

    $entry = $manifest['assets/src/admin/main.js'];

    /*
    |--------------------------------------------------------------------------
    | CSS
    |--------------------------------------------------------------------------
    */
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $cssFile) {
            wp_enqueue_style(
                'corbidev-repositories-admin',
                CDR_PLUGIN_URL . 'assets/dist/' . $cssFile,
                [],
                CDR_VERSION
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | JS
    |--------------------------------------------------------------------------
    */
    if (!empty($entry['file'])) {
        wp_enqueue_script(
            'corbidev-repositories-admin',
            CDR_PLUGIN_URL . 'assets/dist/' . $entry['file'],
            [],
            CDR_VERSION,
            true
        );

        wp_localize_script('corbidev-repositories-admin', 'cdr_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('cdr_nonce')
        ]);
    }
});

/*
|--------------------------------------------------------------------------
| Front / Global Assets via Vite
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function (): void {

    $manifestPath = CDR_PLUGIN_DIR . 'assets/dist/.vite/manifest.json';

    if (!file_exists($manifestPath)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    if (!isset($manifest['assets/src/main.js'])) {
        return;
    }

    $entry = $manifest['assets/src/main.js'];

    /*
    |--------------------------------------------------------------------------
    | CSS
    |--------------------------------------------------------------------------
    */
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $cssFile) {
            wp_enqueue_style(
                'corbidev-repositories-app',
                CDR_PLUGIN_URL . 'assets/dist/' . $cssFile,
                [],
                CDR_VERSION
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | JS
    |--------------------------------------------------------------------------
    */
    if (!empty($entry['file'])) {
        wp_enqueue_script(
            'corbidev-repositories-app',
            CDR_PLUGIN_URL . 'assets/dist/' . $entry['file'],
            [],
            CDR_VERSION,
            true
        );
    }
});