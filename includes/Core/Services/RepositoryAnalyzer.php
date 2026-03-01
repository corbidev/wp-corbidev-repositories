<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Core\Services;

use Corbidev\Repositories\Core\Entity\Repository;

final class RepositoryAnalyzer
{
    public function __construct(private GithubApiClient $client) {}

    public function analyze(Repository $repository): array
    {
        $base = rtrim($repository->apiBaseUrl(), '/');

        $repoData = $this->client->fetch(
            $base . '/repos/' . $repository->owner() . '/' . $repository->name(),
            $repository->token()
        );

        $tags = $this->client->fetch(
            $base . '/repos/' . $repository->owner() . '/' . $repository->name() . '/tags',
            $repository->token()
        );

        $contents = $this->client->fetch(
            $base . '/repos/' . $repository->owner() . '/' . $repository->name() . '/contents',
            $repository->token()
        );

        $isTheme = false;
        foreach ($contents as $file) {
            if (($file['name'] ?? '') === 'style.css') {
                $isTheme = true;
                break;
            }
        }

        return [
            'description' => $repoData['description'] ?? '',
            'latest_tag'  => $tags[0]['name'] ?? null,
            'is_theme'    => $isTheme,
        ];
    }
}
