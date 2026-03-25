<?php

use Corbidev\Repositories\Repository\RepositoryStorage;

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

// IMPORTANT : composant global à conserver
require CDR_PLUGIN_DIR . 'admin/pages/components/modal.php';

// récupération des données
$repos = RepositoryStorage::getRepositories();

// chargement du template

    require  __DIR__ . '/templates/repository-manager.php';
