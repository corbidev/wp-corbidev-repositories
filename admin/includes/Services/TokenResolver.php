<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Admin\Services;

final class TokenResolver
{
    public function resolve(string $repositoryName, ?string $storedToken): ?string
    {
        $envKey = 'TOKEN_' . strtoupper(str_replace('-', '_', $repositoryName));
        $envToken = getenv($envKey);

        if ($envToken !== false && $envToken !== '') {
            return $envToken;
        }

        return $storedToken;
    }
}
