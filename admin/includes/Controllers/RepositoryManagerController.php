<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Repository\RepositoryStorage;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryManagerController
{
    public static function index(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $repos = RepositoryStorage::getRepositories();

        self::render('repository-manager', [
            'repos' => $repos
        ]);
    }

    private static function render(string $template, array $data = []): void
    {
        extract($data);

        $file = CDR_PLUGIN_DIR . 'admin/templates/' . $template . '.php';

        if (file_exists($file)) {
            include $file;
        } else {
            echo 'Template not found';
        }
    }
}