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
    private array $errors = [];

    public function getAll(?string $owner = null, string $type = 'plugin'): array
    {
        $repos = RepositoryManager::all();
        $items = [];
        $scanner = new RepositoryScanner();
        $this->errors = [];

        foreach ($repos as $repo) {

            if (empty($repo['client']) || empty($repo['name'])) {
                continue;
            }

            $client = $repo['client'];
            $repoOwner = $repo['name'];

            if ($owner && $repoOwner !== $owner) {
                continue;
            }

            try {
                $repositories = $client->getRepositories($repoOwner);
            } catch (\Throwable $e) {
                $this->errors[] = [
                    'owner' => $repoOwner,
                    'reason' => $this->detectAccessErrorReason($e),
                ];

                Logger::error(sprintf(
                    'GitHub access failed for owner "%s": %s',
                    $repoOwner,
                    $e->getMessage()
                ));

                continue;
            }

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

                $displayRemoteVersion = $remoteVersion !== null && trim((string) $remoteVersion) !== ''
                    ? trim((string) $remoteVersion)
                    : null;
                $displayInstalledVersion = !empty($scan['version'])
                    ? trim((string) $scan['version'])
                    : null;

                $hasUpdate = false;

                if ($displayInstalledVersion && $displayRemoteVersion) {
                    $hasUpdate = $this->compareVersions(
                        $displayRemoteVersion,
                        $displayInstalledVersion
                    ) > 0;
                }

                $items[] = [
                    'name'              => $name,
                    'slug'              => $scan['slug'] ?? '',
                    'description'       => $r['description'] ?? '',
                    'version'           => $displayRemoteVersion ?: '-',
                    'installed_version' => $displayInstalledVersion,
                    'is_installed'      => $scan['installed'] ?? false,
                    'is_active'         => $scan['active'] ?? false,
                    'has_update'        => $hasUpdate,
                    'owner'             => $repoOwner,
                ];
            }
        }

        return $items;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function filter(string $name, string $type): bool
    {
        if (!str_starts_with($name, 'wp-')) return false;

        if ($type === 'plugin') return !str_contains($name, 'theme');
        if ($type === 'theme') return str_contains($name, 'theme');

        return false;
    }

    private function detectAccessErrorReason(\Throwable $error): string
    {
        if (str_contains($error->getMessage(), 'API rate limit exceeded')) {
            return 'rate_limit';
        }

        return 'unavailable';
    }

    private function compareVersions(string $left, string $right): int
    {
        $normalizedLeft = $this->normalizeVersionForComparison($left);
        $normalizedRight = $this->normalizeVersionForComparison($right);

        $comparison = version_compare($normalizedLeft, $normalizedRight);

        if ($comparison !== 0) {
            return $comparison;
        }

        $leftSnapshot = $this->isSnapshotVersion($left);
        $rightSnapshot = $this->isSnapshotVersion($right);

        if ($leftSnapshot === $rightSnapshot) {
            return 0;
        }

        return $leftSnapshot ? -1 : 1;
    }

    private function normalizeVersionForComparison(string $version): string
    {
        $normalized = ltrim(trim($version), 'vV');
        $normalized = preg_replace('/-SNAPSHOT$/i', '', $normalized) ?? $normalized;

        return $normalized !== '' ? $normalized : $version;
    }

    private function isSnapshotVersion(string $version): bool
    {
        return (bool) preg_match('/-SNAPSHOT$/i', trim($version));
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
