<?php

namespace Corbidev\Repositories\Services;

use Corbidev\Repositories\Repository\RepositoryManager;
use Corbidev\Repositories\Manager\RepositoryInstaller;
use Corbidev\Repositories\Manager\RepositoryActivator;
use Corbidev\Repositories\Manager\RepositoryDelete;
use Corbidev\Repositories\Manager\RepositoryUpdater;
use Corbidev\Repositories\Scanner\RepositoryScanner;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryService
{
    public function getAll(?string $owner = null, string $type = 'plugin'): array
    {
        $repos = RepositoryManager::all();
        $items = [];
        $scanner = new RepositoryScanner();

        foreach ($repos as $repo) {

            if (empty($repo['client']) || empty($repo['name'])) {
                continue;
            }

            $client = $repo['client'];
            $repoOwner = $repo['name'];

            if ($owner && $repoOwner !== $owner) {
                continue;
            }

            $repositories = $client->getRepositories($repoOwner);

            if (!is_array($repositories)) {
                continue;
            }

            foreach ($repositories as $r) {

                $name = $r['name'] ?? '';

                if (!$this->filter($name, $type)) {
                    continue;
                }

                $scan = $scanner->scan($name, $type);

                try {
                    $remoteVersion = $client->getLatestVersion($repoOwner, $name);
                } catch (\Throwable $e) {
                    $remoteVersion = null;
                }

                $hasUpdate = false;

                if (!empty($scan['version']) && $remoteVersion) {
                    $hasUpdate = version_compare($remoteVersion, $scan['version'], '>');
                }

                $items[] = [
                    'name'              => $name,
                    'slug'              => $scan['slug'] ?? '',
                    'description'       => $r['description'] ?? '',
                    'version'           => $remoteVersion ?: '-',
                    'installed_version' => $scan['version'] ?? null,
                    'is_installed'      => $scan['installed'] ?? false,
                    'is_active'         => $scan['active'] ?? false,
                    'has_update'        => $hasUpdate,
                    'owner'             => $repoOwner,
                ];
            }
        }

        return $items;
    }

    private function filter(string $name, string $type): bool
    {
        if (!str_starts_with($name, 'wp-')) return false;

        if ($type === 'plugin') return !str_contains($name, 'theme');
        if ($type === 'theme') return str_contains($name, 'theme');

        return false;
    }

    public function install(string $owner, string $name, string $type): bool
    {
        $repo = RepositoryManager::get($owner);
        if (!$repo || empty($repo['client'])) return false;

        $client = $repo['client'];
        $zip = $client->getZipUrl($owner, $name);

        if (!$zip) return false;

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        ob_start();

        if ($type === 'plugin') {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        } else {
            include_once ABSPATH . 'wp-admin/includes/theme-install.php';
        }

        $result = RepositoryInstaller::install($zip, $name, $type);

        ob_end_clean();

        return (bool) $result;
    }

    public function activate(string $slug): bool
    {
        return RepositoryActivator::activate($slug);
    }

    public function deactivate(string $slug): bool
    {
        return RepositoryActivator::deactivate($slug);
    }

    public function delete(string $slug, string $type): bool
    {
        $deleter = new RepositoryDelete();

        if ($type === 'plugin') return $deleter->deletePlugin($slug);
        if ($type === 'theme') return $deleter->deleteTheme($slug);

        return false;
    }

    public function update(string $owner, string $name, string $type): bool
    {
        $scanner = new RepositoryScanner();
        $scan = $scanner->scan($name, $type);

        if (empty($scan['installed']) || empty($scan['slug'])) {
            return false;
        }

        $repoUrl = "https://github.com/{$owner}/{$name}";
        $slug = $scan['slug'];

        $updater = new RepositoryUpdater();

        return $updater->update($repoUrl, $type, $slug);
    }
}
