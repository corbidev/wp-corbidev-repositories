<?php

namespace Corbidev\Repositories\Repository;

use Corbidev\Repositories\Github\GithubClient;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryManager
{
    /**
     * Retourne tous les repos AVEC client GitHub
     */
    public static function all(): array
    {
        $repos = RepositoryStorage::getRepositories();

        $formatted = [];

        foreach ($repos as $repo) {

            $formatted[] = [
                'name'   => $repo['name'],
                'token'  => $repo['token'] ?? null,
                'client' => new GithubClient($repo['token'] ?? null),
            ];
        }

        return $formatted;
    }

    /**
     * Retourne UN repo AVEC client
     */
    public static function get(string $name): ?array
    {
        $repos = RepositoryStorage::getRepositories();

        if (!isset($repos[$name])) {
            return null;
        }

        $repo = $repos[$name];

        return [
            'name'   => $repo['name'],
            'token'  => $repo['token'] ?? null,
            'client' => new GithubClient($repo['token'] ?? null),
        ];
    }

    public static function add(string $name, string $token = ''): bool
    {
        return RepositoryStorage::addRepository($name, $token);
    }

    public static function update(string $name, string $token = ''): bool
    {
        return RepositoryStorage::updateRepository($name, $token);
    }

    public static function delete(string $name): bool
    {
        return RepositoryStorage::deleteRepository($name);
    }

    public static function isProtected(string $name): bool
    {
        return RepositoryStorage::isProtectedRepository($name);
    }
}
