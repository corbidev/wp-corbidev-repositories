<?php

namespace Corbidev\Repositories\Ajax;

use Corbidev\Repositories\Repository\RepositoryManager;

if (!defined('ABSPATH')) exit;

class RepositoryAdminAjax
{
    public static function register(): void
    {
        add_action('wp_ajax_cdr_repo_add', [self::class, 'add']);
        add_action('wp_ajax_cdr_repo_update', [self::class, 'update']);
        add_action('wp_ajax_cdr_repo_delete', [self::class, 'delete']);
    }

    private static function check(): void
    {
        check_ajax_referer('cdr_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }
    }

    public static function add(): void
    {
        self::check();

        $name  = sanitize_text_field($_POST['name'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');

        if (!$name) {
            wp_send_json_error(['message' => 'Missing name']);
        }

        RepositoryManager::add($name, $token);

        wp_send_json_success();
    }

    public static function update(): void
    {
        self::check();

        RepositoryManager::update(
            sanitize_text_field($_POST['name']),
            sanitize_text_field($_POST['token'])
        );

        wp_send_json_success();
    }

    public static function delete(): void
    {
        self::check();

        RepositoryManager::delete(
            sanitize_text_field($_POST['name'])
        );

        wp_send_json_success();
    }
}