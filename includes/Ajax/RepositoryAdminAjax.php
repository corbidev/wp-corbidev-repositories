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
        check_ajax_referer('corbidev_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Unauthorized', 'corbidevrepositories')]);
        }
    }

    public static function add(): void
    {
        self::check();

        $name  = sanitize_text_field($_POST['name'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');

        if (!$name) {
            wp_send_json_error(['message' => __('Missing repository owner.', 'corbidevrepositories')]);
        }

        if (!RepositoryManager::add($name, $token)) {
            wp_send_json_error(['message' => __('This repository owner is already configured.', 'corbidevrepositories')]);
        }

        wp_send_json_success();
    }

    public static function update(): void
    {
        self::check();

        $name = sanitize_text_field($_POST['name'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');

        if (!$name) {
            wp_send_json_error(['message' => __('Missing repository owner.', 'corbidevrepositories')]);
        }

        if (!RepositoryManager::update($name, $token)) {
            wp_send_json_error(['message' => __('Unable to update this repository access token.', 'corbidevrepositories')]);
        }

        wp_send_json_success();
    }

    public static function delete(): void
    {
        self::check();

        $name = sanitize_text_field($_POST['name'] ?? '');

        if (!$name) {
            wp_send_json_error(['message' => __('Missing repository owner.', 'corbidevrepositories')]);
        }

        if (RepositoryManager::isProtected($name)) {
            wp_send_json_error(['message' => __('The default Corbidev repository cannot be deleted.', 'corbidevrepositories')]);
        }

        if (!RepositoryManager::delete($name)) {
            wp_send_json_error(['message' => __('Unable to delete this repository.', 'corbidevrepositories')]);
        }

        wp_send_json_success();
    }
}
