<?php

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryController
{
    /**
     * Affichage page principale
     */
    public static function index(): void
    {
        // 🔐 sécurité
        if (!current_user_can('install_plugins')) {
            wp_die('Unauthorized');
        }

        // 🔧 type (plugin par défaut)
        $type = isset($_GET['type']) && in_array($_GET['type'], ['plugin', 'theme'])
            ? sanitize_text_field($_GET['type'])
            : 'plugin';

        // 🔧 repo (plus tard dynamique)
        $owner = 'corbidev';

        // 🔧 service
        $service = new RepositoryService();
        $items = $service->getAll($owner, $type);

        // 🔧 rendu
        self::render('repository-list', [
            'type'  => $type,
            'items' => $items,
        ]);
    }

    /**
     * Render générique
     */
    private static function render(string $template, array $data = []): void
    {
        extract($data);

        $file = CDR_PLUGIN_DIR . 'admin/templates/' . $template . '.php';

        if (file_exists($file)) {
            include $file;
        } else {
            echo 'Template not found: ' . esc_html($template);
        }
    }
}
