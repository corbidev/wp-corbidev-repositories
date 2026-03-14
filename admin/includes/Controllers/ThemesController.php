<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Services\ThemeService;
use Corbidev\Repositories\Installer\ThemeInstaller;

if (!defined('ABSPATH')) {
    exit;
}

class ThemesController
{
    public static function render(): void
    {
        $themes = ThemeService::list();

        /*
         |--------------------------------------------------------------------------
         | Actions (install / activate)
         |--------------------------------------------------------------------------
         */

        if (isset($_GET['install_theme'])) {

            $repo = sanitize_text_field($_GET['install_theme']);

            ThemeInstaller::install($repo);

        }

        if (isset($_GET['activate_theme'])) {

            $repo = sanitize_text_field($_GET['activate_theme']);

            ThemeInstaller::activate($repo);

        }

        require CDR_PLUGIN_DIR . 'admin/pages/themes.php';
    }
}
