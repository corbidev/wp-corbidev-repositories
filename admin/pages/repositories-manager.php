<?php

use Corbidev\Repositories\Repository\RepositoryStorage;

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

// récupération des données
$repos = RepositoryStorage::getRepositories();

// chargement du template
require __DIR__ . '/templates/repository-manager-shadcn.php';
