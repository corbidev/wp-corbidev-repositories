<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Services\PluginService;
use Corbidev\Repositories\Installer\PluginInstaller;

if (!defined('ABSPATH')) {
    exit;
}

class PluginsController
{
    public static function render(): void
    {
        if (isset($_GET['install'])) {

            $repo = sanitize_text_field($_GET['install']);

            PluginInstaller::install($repo);

            echo '<div class="updated"><p>Plugin installé</p></div>';
        }

        $plugins = PluginService::list();

        require CDR_PLUGIN_DIR . 'admin/pages/plugins.php';
    }
}
