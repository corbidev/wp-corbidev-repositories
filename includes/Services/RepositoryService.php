<?php

namespace Corbidev\Repositories\Services;

use Corbidev\Repositories\Github\GithubClient;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryService
{
    private GithubClient $client;

    public function __construct(?string $token = null)
    {
        $this->client = new GithubClient($token);
    }

    private function filter(array $repos, string $type): array
    {
        return array_filter($repos, function ($repo) use ($type) {
            $name = $repo['name'] ?? '';

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
        });
    }

    private function format(array $repo, string $owner): array
    {
        $name = $repo['name'];

        return [
            'name'        => $name,
            'slug'        => $name,
            'description' => $repo['description'] ?? '',
            'version'     => $this->client->getLatestTag($owner, $name),
            'zip'         => $this->client->getZipUrl($owner, $name),
        ];
    }

    public function getAll(string $owner, string $type): array
    {
        $repos = $this->client->getRepositories($owner);

        $filtered = $this->filter($repos, $type);

        return array_map(fn($repo) => $this->format($repo, $owner), $filtered);
    }

    public function install(string $owner, string $name, string $type): bool
    {
        $zip = $this->client->getZipUrl($owner, $name);

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        if ($type === 'plugin') {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            $upgrader = new \Plugin_Upgrader();
        } else {
            include_once ABSPATH . 'wp-admin/includes/theme-install.php';
            $upgrader = new \Theme_Upgrader();
        }

        $result = $upgrader->install($zip);

        return !is_wp_error($result);
    }
}
