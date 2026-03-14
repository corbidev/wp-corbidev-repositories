<?php

namespace Corbidev\Repositories\Admin\Ajax;

use Corbidev\Repositories\Installer\PluginInstaller;

if (!defined('ABSPATH')) {
    exit;
}

class PluginAjax
{
    public static function init(): void
    {
        add_action('wp_ajax_corbidev_install_plugin', [self::class, 'install']);
        add_action('wp_ajax_corbidev_activate_plugin', [self::class, 'activate']);
    }

    public static function install(): void
    {
        check_ajax_referer('corbidev_nonce', 'nonce');

        if (!current_user_can('install_plugins')) {
            wp_send_json_error([
                'message' => 'Permission denied'
            ]);
        }

        $repo = sanitize_text_field($_POST['repo'] ?? '');

        if (!$repo) {
            wp_send_json_error([
                'message' => 'Invalid repository'
            ]);
        }

        $result = PluginInstaller::install($repo);

        if (is_wp_error($result)) {

            wp_send_json_error([
                'message' => $result->get_error_message()
            ]);

        }

        if ($result === false) {

            wp_send_json_error([
                'message' => 'Plugin installation failed'
            ]);

        }

        wp_send_json_success([
            'message' => 'Plugin installed'
        ]);
    }

    public static function activate(): void
    {
        check_ajax_referer('corbidev_nonce', 'nonce');

        if (!current_user_can('activate_plugins')) {
            wp_send_json_error([
                'message' => 'Permission denied'
            ]);
        }

        $repo = sanitize_text_field($_POST['repo'] ?? '');

        if (!$repo) {
            wp_send_json_error([
                'message' => 'Invalid repository'
            ]);
        }

        $pluginFile = PluginInstaller::getPluginFile($repo);

        if (!$pluginFile) {

            wp_send_json_error([
                'message' => 'Plugin file not found'
            ]);

        }

        $result = PluginInstaller::activate($pluginFile);

        if (is_wp_error($result)) {

            wp_send_json_error([
                'message' => $result->get_error_message()
            ]);

        }

        wp_send_json_success([
            'message' => 'Plugin activated'
        ]);
    }
}