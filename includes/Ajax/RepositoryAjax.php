<?php

namespace Corbidev\Repositories\Ajax;

use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) exit;

class RepositoryAjax
{
    public static function register(): void
    {
        add_action('wp_ajax_cdr_install_item', [self::class, 'install']);
        add_action('wp_ajax_cdr_activate_item', [self::class, 'activate']);
        add_action('wp_ajax_cdr_deactivate_item', [self::class, 'deactivate']);
        add_action('wp_ajax_cdr_delete_item', [self::class, 'delete']);
        add_action('wp_ajax_cdr_update_item', [self::class, 'update']);
    }

    private static function check()
    {
        check_ajax_referer('corbidev_nonce', 'nonce');
    }

    public static function install()
    {
        self::check();

        $service = new RepositoryService();

        wp_send_json_success([
            'result' => $service->install($_POST['owner'], $_POST['name'], $_POST['type'])
        ]);
    }

    public static function activate()
    {
        self::check();

        $service = new RepositoryService();

        wp_send_json_success([
            'result' => $service->activate($_POST['name']) // slug
        ]);
    }

    public static function deactivate()
    {
        self::check();

        $service = new RepositoryService();

        wp_send_json_success([
            'result' => $service->deactivate($_POST['name']) // slug
        ]);
    }

    public static function delete()
    {
        self::check();

        $service = new RepositoryService();

        wp_send_json_success([
            'result' => $service->delete($_POST['name'], $_POST['type']) // slug
        ]);
    }

    public static function update()
    {
        self::check();

        $service = new RepositoryService();

        wp_send_json_success([
            'result' => $service->update($_POST['owner'], $_POST['name'], $_POST['type']) // repo name
        ]);
    }
}