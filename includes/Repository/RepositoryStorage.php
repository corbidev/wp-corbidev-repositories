<?php

namespace Corbidev\Repositories\Repository;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryStorage
{
    private const OPTION_KEY = 'cdr_repositories';

    /**
     * Récupère les dépôts
     */
    public static function getRepositories(): array
    {
        $repos = self::getOption();

        if (!is_array($repos)) {
            return [];
        }

        return $repos;
    }

    /**
     * Ajoute un dépôt
     */
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

    /**
     * Met à jour un dépôt
     */
    public static function updateRepository(string $name, string $token = ''): bool
    {
        $repos = self::getRepositories();

        if (!isset($repos[$name])) {
            return false;
        }

        $repos[$name]['token'] = $token;

        return self::updateOption($repos);
    }

    /**
     * Supprime un dépôt
     */
    public static function deleteRepository(string $name): bool
    {
        $repos = self::getRepositories();

        if (!isset($repos[$name])) {
            return false;
        }

        unset($repos[$name]);

        return self::updateOption($repos);
    }

    /**
     * Récupère option WP (multi / standard)
     */
    private static function getOption()
    {
        if (is_multisite()) {
            return get_site_option(self::OPTION_KEY, []);
        }

        return get_option(self::OPTION_KEY, []);
    }

    /**
     * Sauvegarde option WP (multi / standard)
     */
    private static function updateOption(array $value): bool
    {
        if (is_multisite()) {
            return update_site_option(self::OPTION_KEY, $value);
        }

        return update_option(self::OPTION_KEY, $value);
    }
}