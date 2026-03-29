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
| Manifest (GLOBAL UNIQUE)
|--------------------------------------------------------------------------
*/
global $cdr_manifest;

$manifest_path = CDR_PLUGIN_DIR . 'assets/dist/.vite/manifest.json';

$cdr_manifest = file_exists($manifest_path)
    ? json_decode(file_get_contents($manifest_path), true)
    : [];

function corbidev_get_manifest(): array
{
    global $cdr_manifest;
    return $cdr_manifest ?? [];
}

/*
|--------------------------------------------------------------------------
| 🔥 GLOBAL FIX : ES MODULES (CRITIQUE)
|--------------------------------------------------------------------------
*/
add_filter('script_loader_tag', function ($tag, $handle, $src) {

    if (str_starts_with($handle, 'corbidev')) {
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }

    return $tag;

}, 10, 3);

/*
|--------------------------------------------------------------------------
| Init Plugin
|--------------------------------------------------------------------------
*/
add_action('plugins_loaded', function () {

    \Corbidev\Repositories\Core\Plugin::init();

    \Corbidev\Repositories\Ajax\RepositoryAjax::register();
    \Corbidev\Repositories\Ajax\RepositoryAdminAjax::register();

});