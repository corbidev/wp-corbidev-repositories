<?php

namespace Corbidev\Repositories\Repository;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryManager
{
    public static function all(): array
    {
        return RepositoryStorage::getRepositories();
    }

    public static function get(string $name): ?array
    {
        $repos = RepositoryStorage::getRepositories();

        return $repos[$name] ?? null;
    }

    public static function add(string $name, string $token = ''): bool
    {
        return RepositoryStorage::addRepository(
            sanitize_text_field($name),
            sanitize_text_field($token)
        );
    }

    public static function update(string $name, string $token): bool
    {
        return RepositoryStorage::updateRepository(
            sanitize_text_field($name),
            sanitize_text_field($token)
        );
    }

    public static function delete(string $name): bool
    {
        return RepositoryStorage::deleteRepository(
            sanitize_text_field($name)
        );
    }
}
