<?php

namespace Corbidev\Repositories\Scanner;

use Corbidev\Repositories\Github\GithubClient;

if (!defined('ABSPATH')) {
    exit;
}

class ThemeScanner
{
    public static function scan(): array
    {
        $client = new GithubClient();

        $repos = self::fetchRepos($client);

        if (!is_array($repos)) {
            return [];
        }

        $themes = [];

        foreach ($repos as $repo) {

            if (!is_array($repo)) {
                continue;
            }

            if (!isset($repo['name'])) {
                continue;
            }

            $name = $repo['name'];

            if (!str_starts_with($name, 'wp-')) {
                continue;
            }

            if (!str_contains($name, 'theme')) {
                continue;
            }

            $themes[] = [
                'name'        => $name,
                'description' => $repo['description'] ?? '',
                'version'     => self::getVersion($client, $name),
                'url'         => $repo['html_url'] ?? '',
            ];
        }

        return $themes;
    }

    private static function fetchRepos(GithubClient $client)
    {
        $repos = $client->get('/orgs/corbidev/repos');

        if (!is_array($repos) || isset($repos['message'])) {
            $repos = $client->get('/users/corbidev/repos');
        }

        return $repos;
    }

    private static function getVersion(GithubClient $client, string $repo): string
    {
        $tags = $client->get('/repos/corbidev/' . $repo . '/tags');

        if (!is_array($tags) || empty($tags)) {
            return 'dev';
        }

        $first = $tags[0];

        if (!isset($first['name'])) {
            return 'dev';
        }

        return $first['name'];
    }
}
