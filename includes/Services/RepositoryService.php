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

    /**
     * Filtre plugins / thèmes
     */
    private function filter(array $repos, string $type): array
    {
        return array_filter($repos, function ($repo) use ($type) {

            $name = $repo['name'] ?? '';

            if (!$name || !str_starts_with($name, 'wp-')) {
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

    /**
     * Format des données pour affichage
     */
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

    /**
     * Récupération des repos
     */
    public function getAll(string $owner, string $type): array
    {
        $repos = $this->client->getRepositories($owner);

        if (!is_array($repos)) {
            return [];
        }

        $filtered = $this->filter($repos, $type);

        return array_map(fn($repo) => $this->format($repo, $owner), $filtered);
    }

    /**
     * Installation plugin / thème
     */
    public function install(string $owner, string $name, string $type): bool
    {
        $zip = $this->client->getZipUrl($owner, $name);

        if (!$zip) {
            return false;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // 🔥 Empêche WordPress d'afficher du HTML (CRITIQUE POUR AJAX)
        ob_start();

        try {

            if ($type === 'plugin') {

                include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

                $upgrader = new \Plugin_Upgrader(
                    new \Automatic_Upgrader_Skin() // 🔥 évite output HTML
                );

            } else {

                include_once ABSPATH . 'wp-admin/includes/theme-install.php';

                $upgrader = new \Theme_Upgrader(
                    new \Automatic_Upgrader_Skin() // 🔥 évite output HTML
                );
            }

            $result = $upgrader->install($zip);

        } catch (\Throwable $e) {

            // 🔥 log utile
            error_log('[CDR INSTALL ERROR] ' . $e->getMessage());

            ob_end_clean();

            return false;
        }

        // 🔥 nettoie toute sortie parasite
        ob_end_clean();

        // 🔥 gestion WP_Error
        if (is_wp_error($result)) {
            error_log('[CDR WP ERROR] ' . $result->get_error_message());
            return false;
        }

        return (bool) $result;
    }
}
