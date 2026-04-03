<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryController
{
    public static function index(): void
    {
        if (!current_user_can('install_plugins')) {
            wp_die(__('Unauthorized', 'corbidev'));
        }

        $type = isset($_GET['type'])
            ? sanitize_text_field($_GET['type'])
            : 'plugin';

        $owner = isset($_GET['owner'])
            ? sanitize_text_field($_GET['owner'])
            : null;

        $service = new RepositoryService();
        $items = $service->getAll($owner, $type);
        $errors = $service->getErrors();

        include CDR_PLUGIN_DIR . 'admin/pages/repositories.php';
    }
}
