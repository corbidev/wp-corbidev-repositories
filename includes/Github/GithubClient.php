<?php

namespace Corbidev\Repositories\Github;

use Corbidev\Repositories\Cache\GithubCache;

if (!defined('ABSPATH')) {
    exit;
}

class GithubClient
{
    private string $baseUrl = 'https://api.github.com';
    private ?string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    /**
     * Requête HTTP vers GitHub
     */
    private function request(string $endpoint): array
    {
        $url = $this->baseUrl . $endpoint;

        $args = [
            'headers' => [
                'Accept'        => 'application/vnd.github+json',
                'User-Agent'    => 'WordPress-Corbidev',
            ],
            'timeout' => 20,
        ];

        if (!empty($this->token)) {
            $args['headers']['Authorization'] = 'Bearer ' . $this->token;
        }

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code !== 200) {
            throw new \Exception("GitHub API error ({$code}) : {$body}");
        }

        return json_decode($body, true) ?? [];
    }

    /**
     * Liste des repos d'une organisation
     */
    public function getRepositories(string $owner): array
    {
        $cacheKey = "repos_{$owner}";

        return GithubCache::remember($cacheKey, function () use ($owner) {
            return $this->request("/users/{$owner}/repos");
        }, 300);
    }

    /**
     * Contenu d’un repo
     */
    public function getContents(string $owner, string $repo, string $path = ''): array
    {
        $cacheKey = "contents_{$owner}_{$repo}_" . md5($path);

        return GithubCache::remember($cacheKey, function () use ($owner, $repo, $path) {
            return $this->request("/repos/{$owner}/{$repo}/contents/{$path}");
        }, 300);
    }

    /**
     * Tags (versions)
     */
    public function getTags(string $owner, string $repo): array
    {
        $cacheKey = "tags_{$owner}_{$repo}";

        return GithubCache::remember($cacheKey, function () use ($owner, $repo) {
            return $this->request("/repos/{$owner}/{$repo}/tags");
        }, 300);
    }

    /**
     * Dernier tag (version)
     */
    public function getLatestTag(string $owner, string $repo): ?string
    {
        $tags = $this->getTags($owner, $repo);

        if (empty($tags)) {
            return null;
        }

        return $tags[0]['name'] ?? null;
    }

    /**
     * Téléchargement ZIP d’un repo
     */
    public function getZipUrl(string $owner, string $repo, ?string $ref = null): string
    {
        $ref = $ref ?? 'main';

        return "https://github.com/{$owner}/{$repo}/archive/refs/heads/{$ref}.zip";
    }

    /**
     * Releases
     */
    public function getReleases(string $owner, string $repo): array
    {
        $cacheKey = "releases_{$owner}_{$repo}";

        return GithubCache::remember($cacheKey, function () use ($owner, $repo) {
            return $this->request("/repos/{$owner}/{$repo}/releases");
        }, 600);
    }

    /**
     * Dernière release
     */
    public function getLatestRelease(string $owner, string $repo): ?array
    {
        $releases = $this->getReleases($owner, $repo);

        if (empty($releases)) {
            return null;
        }

        return $releases[0];
    }
}
