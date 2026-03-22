<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Repository\RepositoryStorage;

if (!defined('ABSPATH')) exit;

class RepositoryAdminController
{
    public static function index(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $repositories = RepositoryStorage::getRepositories();

        include CDR_PLUGIN_DIR . 'admin/pages/repositories-manager.php';
    }
}