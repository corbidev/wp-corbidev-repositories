<?php

use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) {
    exit;
}

// 🔐 Sécurité admin
if (!current_user_can('install_plugins')) {
    wp_die('Unauthorized');
}

// 🔧 Type (plugin par défaut)
$type = isset($_GET['type']) && in_array($_GET['type'], ['plugin', 'theme'])
    ? $_GET['type']
    : 'plugin';

// 🔧 Owner (plus tard dynamique)
$owner = 'corbidev';

// 🔧 Service
$service = new RepositoryService();
$items = $service->getAll($owner, $type);

// 🔧 Template
include CDR_PLUGIN_DIR . 'admin/templates/repository-list.php';

require CDR_PLUGIN_DIR . 'admin/pages/components/modal.php';
