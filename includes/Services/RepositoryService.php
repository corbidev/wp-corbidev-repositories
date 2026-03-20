<?php

namespace Corbidev\Repositories\Services;

use Corbidev\Repositories\Repository\RepositoryManager;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryService
{
    /**
     * Récupère tous les items depuis TOUS les repos
     */
    public function getAll(string $type): array
    {
        $repos = RepositoryManager::all();

        $items = [];

        foreach ($repos as $repo) {

            $client = $repo['client'];
            $owner  = $repo['name'];

            $repositories = $client->getRepositories($owner);

            if (!is_array($repositories)) {
                continue;
            }

            foreach ($repositories as $r) {

                $name = $r['name'] ?? '';

                if (!$this->filter($name, $type)) {
                    continue;
                }

                $items[] = [
                    'name'        => $name,
                    'slug'        => $name,
                    'description' => $r['description'] ?? '',
                    'version'     => $client->getLatestTag($owner, $name),
                    'owner'       => $owner,
                ];
            }
        }

        return $items;
    }

    private function filter(string $name, string $type): bool
    {
        if (!str_starts_with($name, 'wp-')) {
            return false;
        }

        if ($type === 'plugin') {
            return !str_contains($name, 'theme');
        }

        if ($type === 'theme') {
            return str_contains($name, 'theme');
        }

        return false;
    }

    /**
     * Installation
     */
    public function install(string $owner, string $name, string $type): bool
    {
        $repo = RepositoryManager::get($owner);

        if (!$repo) {
            return false;
        }

        $client = $repo['client'];
        $zip = $client->getZipUrl($owner, $name);

        if (!$zip) {
            return false;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        ob_start();

        if ($type === 'plugin') {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());
        } else {
            include_once ABSPATH . 'wp-admin/includes/theme-install.php';
            $upgrader = new \Theme_Upgrader(new \Automatic_Upgrader_Skin());
        }

        $result = $upgrader->install($zip);

        ob_end_clean();

        return !is_wp_error($result);
    }
}
