<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can(is_multisite() ? 'manage_network_options' : 'manage_options')) {
    return;
}

use Corbidev\Repositories\Admin\Services\RepositoryStorage;

$storage = new RepositoryStorage();
$repositories = $storage->get();

$errorMessage = null;

/*
|--------------------------------------------------------------------------
| Handle POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('corbidev_repositories_action', 'corbidev_repositories_nonce');

    $owner       = sanitize_text_field($_POST['owner'] ?? '');
    $repository  = sanitize_text_field($_POST['repository'] ?? '');
    $token       = sanitize_text_field($_POST['token'] ?? '');
    $apiBaseUrl  = sanitize_text_field($_POST['api_base_url'] ?? 'https://api.github.com');
    $editIndex   = isset($_POST['edit_index']) && $_POST['edit_index'] !== '' ? (int) $_POST['edit_index'] : null;

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    if (isset($_POST['delete_index'])) {

        $index = (int) $_POST['delete_index'];

        if (isset($repositories[$index])) {
            unset($repositories[$index]);
            $repositories = array_values($repositories);
            $storage->save($repositories);
        }

    /*
    |--------------------------------------------------------------------------
    | Add / Update
    |--------------------------------------------------------------------------
    */
    } elseif (!empty($owner) && !empty($repository)) {

        $duplicate = false;

        foreach ($repositories as $i => $repo) {
            if (
                strtolower($repo['owner']) === strtolower($owner) &&
                strtolower($repo['repository']) === strtolower($repository) &&
                $i !== $editIndex
            ) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {

            $errorMessage = __('This repository already exists.', 'corbidevrepositories');

        } else {

            $data = [
                'owner'        => $owner,
                'repository'   => $repository,
                'token'        => $token,
                'api_base_url' => $apiBaseUrl ?: 'https://api.github.com',
            ];

            if ($editIndex !== null && isset($
