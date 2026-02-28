<?php
declare(strict_types=1);
namespace Corbidev\Repositories\Core;
use Corbidev\Repositories\Core\Services\RepositoryValidator;
use Corbidev\Repositories\Core\Services\RepositoryAnalyzer;
use Corbidev\Repositories\Core\Services\CacheManager;
final class RepositoryManager {
    public function __construct(
        private RepositoryValidator $validator,
        private RepositoryAnalyzer $analyzer,
        private CacheManager $cache
    ) {}
    public function analyze(string $owner, string $repository, ?string $token = null, string $apiBaseUrl = 'https://api.github.com'): array {
        $this->validator->validate($owner, $repository);
        return $this->analyzer->analyze(
            new \Corbidev\Repositories\Core\Entity\Repository($owner, $repository, $token, $apiBaseUrl)
        );
    }
}
