<?php
declare(strict_types=1);
namespace Corbidev\Repositories\Core\Services;
use Corbidev\Repositories\Core\Exceptions\RepositoryException;
final class RepositoryValidator {
    public function validate(string $owner, string $repository): void {
        if (empty($owner)) throw new RepositoryException('invalid_owner');
        if (empty($repository)) throw new RepositoryException('invalid_repository');
    }
}
