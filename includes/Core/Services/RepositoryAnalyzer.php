<?php
declare(strict_types=1);
namespace Corbidev\Repositories\Core\Services;
use Corbidev\Repositories\Core\Entity\Repository;
final class RepositoryAnalyzer {
    public function __construct(private GithubApiClient $client) {}
    public function analyze(Repository $repository): array {
        $base = rtrim($repository->getApiBaseUrl(), '/');
        $repoData = $this->client->request($base . '/repos/' . $repository->getOwner() . '/' . $repository->getName());
        return [
            'description' => $repoData['description'] ?? '',
            'latest_tag'  => null,
            'is_theme'    => false,
        ];
    }
}
