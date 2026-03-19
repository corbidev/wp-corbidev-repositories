<?php

namespace Corbidev\Repositories\Core;

if (!defined('ABSPATH')) {
    exit;
}

class AdminAssets
{
    public static function register(): void
    {
        add_action('admin_enqueue_scripts', [self::class, 'enqueue']);
    }

    public static function enqueue(string $hook): void
    {
        if (!self::isCorbidevPage($hook)) {
            return;
        }

        wp_enqueue_script(
            'cdr-admin',
            CDR_URL . 'assets/dist/admin/main.js',
            [],
            file_exists(CDR_PATH . 'assets/dist/admin/main.js')
                ? filemtime(CDR_PATH . 'assets/dist/admin/main.js')
                : '1.0',
            true
        );

        wp_localize_script('cdr-admin', 'cdr_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('cdr_nonce')
        ]);
    }

    private static function isCorbidevPage(string $hook): bool
    {
        return str_contains($hook, 'corbidev');
    }
}