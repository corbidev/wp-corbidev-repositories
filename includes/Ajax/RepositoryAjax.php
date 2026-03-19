<?php

namespace Corbidev\Repositories\Ajax;

use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryAjax
{
    public static function register(): void
    {
        add_action('wp_ajax_cdr_get_items', [self::class, 'getItems']);
        add_action('wp_ajax_cdr_install_item', [self::class, 'install']);
    }

    private static function check(): void
    {
        check_ajax_referer('cdr_nonce', 'nonce');

        if (!current_user_can('install_plugins')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }
    }

    public static function getItems(): void
    {
        self::check();

        $type  = sanitize_text_field($_POST['type'] ?? '');
        $owner = sanitize_text_field($_POST['owner'] ?? 'corbidev');

        if (!in_array($type, ['plugin', 'theme'])) {
            wp_send_json_error(['message' => 'Invalid type']);
        }

        $service = new RepositoryService();
        $items = $service->getAll($owner, $type);

        wp_send_json_success(['items' => $items]);
    }

    public static function install(): void
    {
        self::check();

        $type  = sanitize_text_field($_POST['type'] ?? '');
        $owner = sanitize_text_field($_POST['owner'] ?? '');
        $name  = sanitize_text_field($_POST['name'] ?? '');

        if (!$type || !$owner || !$name) {
            wp_send_json_error(['message' => 'Missing params']);
        }

        $service = new RepositoryService();

        $result = $service->install($owner, $name, $type);

        $result
            ? wp_send_json_success()
            : wp_send_json_error(['message' => 'Install failed']);
    }
}