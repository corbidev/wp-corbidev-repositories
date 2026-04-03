<?php

namespace Corbidev\Repositories\Manager;

use Corbidev\Repositories\Github\GithubClient;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryUpdater
{
    private RepositoryInstaller $installer;
    private RepositoryDelete $deleter;
    private GithubClient $client;

    public function __construct(?GithubClient $client = null)
    {
        $this->installer = new RepositoryInstaller();
        $this->deleter   = new RepositoryDelete();
        $this->client    = $client ?? new GithubClient();
    }

    /**
     * Update plugin or theme from GitHub
     *
     * @param string      $repoUrl ex: https://github.com/owner/repo
     * @param string      $type    plugin|theme
     * @param string|null $slug    WP slug (IMPORTANT)
     */
    public function update(string $repoUrl, string $type, ?string $slug = null): bool
    {
        // 1. Parse repo
        $parsed = $this->parseRepoUrl($repoUrl);

        if (!$parsed) {
            return false;
        }

        [$owner, $repo] = $parsed;

        // 2. ZIP GitHub
        $zipUrl = $this->client->getZipUrl($owner, $repo);

        if (!$zipUrl) {
            return false;
        }

        // ⚠️ slug final (critique)
        $targetSlug = $this->resolveTargetSlug($type, $repo);

        // 3. Installer nouvelle version
        $installed = $this->installer->install($zipUrl, $targetSlug, $type);

        if (!$installed) {
            return false;
        }

        // 4. Supprimer ancien (APRÈS succès)
        if ($slug && $this->shouldDeletePreviousInstall($type, $slug, $targetSlug)) {
            if ($type === 'plugin') {
                $this->deleter->deletePlugin($slug);
            } elseif ($type === 'theme') {
                $this->deleter->deleteTheme($slug);
            }
        }

        return true;
    }

    /**
     * Parse GitHub repo URL → [owner, repo]
     */
    private function parseRepoUrl(string $repoUrl): ?array
    {
        $parts = parse_url($repoUrl);

        if (empty($parts['path'])) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', $parts['path'])));

        if (count($segments) < 2) {
            return null;
        }

        return [$segments[0], $segments[1]];
    }

    private function resolveTargetSlug(string $type, string $repo): string
    {
        if ($type === 'plugin') {
            return basename(trim(str_replace('\\', '/', $repo), '/'));
        }

        return basename(trim($repo, '/'));
    }

    private function shouldDeletePreviousInstall(string $type, string $installedSlug, string $targetSlug): bool
    {
        $previousDirectorySlug = $this->extractDirectorySlug($type, $installedSlug);

        return $previousDirectorySlug !== '' && $previousDirectorySlug !== $targetSlug;
    }

    private function extractDirectorySlug(string $type, string $slug): string
    {
        $normalized = trim(str_replace('\\', '/', $slug), '/');

        if ($type === 'plugin') {
            $directory = dirname($normalized);

            if ($directory !== '.' && $directory !== DIRECTORY_SEPARATOR) {
                $normalized = $directory;
            }
        }

        return basename($normalized);
    }
}
