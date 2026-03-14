<?php

namespace Corbidev\Repositories\Admin\Ajax;

use Corbidev\Repositories\Installer\PluginInstaller;

if (!defined('ABSPATH')) {
    exit;
}

class PluginAjax
{
    public static function init()
    {
        add_action('wp_ajax_corbidev_install_plugin', [self::class, 'install']);
        add_action('wp_ajax_corbidev_activate_plugin', [self::class, 'activate']);
    }

    public static function install()
    {
        $repo = sanitize_text_field($_POST['repo']);

        $result = PluginInstaller::install($repo);

        wp_send_json([
            'success' => $result
        ]);
    }

    public static function activate()
    {
        $repo = sanitize_text_field($_POST['repo']);

        $plugin = PluginInstaller::getPluginFile($repo);

        if ($plugin) {
            PluginInstaller::activate($plugin);
        }

        wp_send_json([
            'success' => true
        ]);
    }
}