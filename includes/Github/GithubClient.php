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
     * HTTP request to GitHub API
     */
    private function request(string $endpoint): array
    {
        $url = $this->baseUrl . $endpoint;

        $args = [
            'headers' => [
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'WordPress-Corbidev',
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

        $data = json_decode($body, true);

        if (!is_array($data)) {
            throw new \Exception('Invalid JSON from GitHub');
        }

        return $data;
    }

    /**
     * Get repositories of owner
     */
    public function getRepositories(string $owner): array
    {
        $cacheKey = "repos_{$owner}";

        return GithubCache::remember($cacheKey, function () use ($owner) {
            return $this->request("/users/{$owner}/repos");
        }, 300);
    }

    /**
     * Get repository contents
     */
    public function getContents(string $owner, string $repo, string $path = ''): array
    {
        $cacheKey = "contents_{$owner}_{$repo}_" . md5($path);

        return GithubCache::remember($cacheKey, function () use ($owner, $repo, $path) {
            return $this->request("/repos/{$owner}/{$repo}/contents/{$path}");
        }, 300);
    }

    /**
     * Get tags
     */
    public function getTags(string $owner, string $repo): array
    {
        $cacheKey = "tags_{$owner}_{$repo}";

        return GithubCache::remember($cacheKey, function () use ($owner, $repo) {
            return $this->request("/repos/{$owner}/{$repo}/tags");
        }, 300);
    }

    /**
     * Get latest tag (sorted)
     */
    public function getLatestTag(string $owner, string $repo): ?string
    {
        $tags = $this->getTags($owner, $repo);

        if (empty($tags)) {
            return null;
        }

        // 🔥 FIX: sort tags (GitHub does not guarantee order)
        usort($tags, fn($a, $b) => strcmp($b['name'], $a['name']));

        return $tags[0]['name'] ?? null;
    }

    /**
     * Get releases
     */
    public function getReleases(string $owner, string $repo): array
    {
        $cacheKey = "releases_{$owner}_{$repo}";

        return GithubCache::remember($cacheKey, function () use ($owner, $repo) {
            return $this->request("/repos/{$owner}/{$repo}/releases");
        }, 600);
    }

    /**
     * Get latest release
     */
    public function getLatestRelease(string $owner, string $repo): ?array
    {
        $releases = $this->getReleases($owner, $repo);

        if (empty($releases)) {
            return null;
        }

        return $releases[0];
    }

    /**
     * Get latest version (release > tag fallback)
     */
    public function getLatestVersion(string $owner, string $repo): ?string
    {
        $release = $this->getLatestRelease($owner, $repo);

        if (!empty($release['tag_name'])) {
            return $release['tag_name'];
        }

        return $this->getLatestTag($owner, $repo);
    }

    /**
     * Get best ZIP URL (stable & public)
     */
    public function getZipUrl(string $owner, string $repo, ?string $ref = null): string
    {
        // 🔥 FIX: use public GitHub URL (NOT zipball_url API)
        $release = $this->getLatestRelease($owner, $repo);

        if (!empty($release['tag_name'])) {
            return "https://github.com/{$owner}/{$repo}/archive/refs/tags/{$release['tag_name']}.zip";
        }

        if ($ref === null) {
            $ref = $this->getLatestTag($owner, $repo);
        }

        if (!empty($ref)) {
            return "https://github.com/{$owner}/{$repo}/archive/refs/tags/{$ref}.zip";
        }

        // fallback
        return "https://github.com/{$owner}/{$repo}/archive/refs/heads/main.zip";
    }
}
