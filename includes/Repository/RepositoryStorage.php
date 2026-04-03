<?php

namespace Corbidev\Repositories\Repository;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryStorage
{
    private const OPTION_KEY = 'cdr_repositories';
    public const DEFAULT_REPOSITORY_NAME = 'corbidev';

    public static function getRepositories(): array
    {
        $repos = self::getOption();

        if (!is_array($repos)) {
            $repos = [];
        }

        /**
         * 🔥 FIX : repo par défaut
         */
        if (!isset($repos[self::DEFAULT_REPOSITORY_NAME])) {
            $repos[self::DEFAULT_REPOSITORY_NAME] = [
                'name'  => self::DEFAULT_REPOSITORY_NAME,
                'token' => '',
            ];

            self::updateOption($repos);
        }

        return $repos;
    }

    public static function addRepository(string $name, string $token = ''): bool
    {
        $repos = self::getRepositories();

        if (isset($repos[$name])) {
            return false;
        }

        $repos[$name] = [
            'name'  => $name,
            'token' => $token,
        ];

        return self::updateOption($repos);
    }

    public static function updateRepository(string $name, string $token = ''): bool
    {
        $repos = self::getRepositories();

        if (!isset($repos[$name])) {
            return false;
        }

        $repos[$name]['token'] = $token;

        return self::updateOption($repos);
    }

    public static function deleteRepository(string $name): bool
    {
        $repos = self::getRepositories();

        /**
         * 🔥 sécurité : empêcher suppression du repo principal ?
         * (tu peux autoriser si tu veux)
         */

        if (self::isProtectedRepository($name) || !isset($repos[$name])) {
            return false;
        }

        unset($repos[$name]);

        return self::updateOption($repos);
    }

    public static function isProtectedRepository(string $name): bool
    {
        return $name === self::DEFAULT_REPOSITORY_NAME;
    }

    private static function getOption()
    {
        return is_multisite()
            ? get_site_option(self::OPTION_KEY, [])
            : get_option(self::OPTION_KEY, []);
    }

    private static function updateOption(array $value): bool
    {
        return is_multisite()
            ? update_site_option(self::OPTION_KEY, $value)
            : update_option(self::OPTION_KEY, $value);
    }
}
