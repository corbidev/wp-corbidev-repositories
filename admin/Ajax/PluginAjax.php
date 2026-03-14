<?php
namespace Corbidev\Repositories\Admin\Ajax;

use Corbidev\Repositories\Installer\PluginInstaller;
use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class PluginAjax
{
    public static function install(): void
    {
        $repo = sanitize_text_field($_POST['repo'] ?? '');

        Logger::info("AJAX install plugin: " . $repo);

        $result = PluginInstaller::install($repo);

        if (!$result) {
            Logger::error("Plugin install failed: " . $repo);
            wp_send_json_error();
        }

        wp_send_json_success();
    }
}
