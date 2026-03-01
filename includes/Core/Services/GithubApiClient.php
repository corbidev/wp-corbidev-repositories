<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Core\Services;

use Corbidev\Repositories\Core\Exceptions\RepositoryException;

final class GithubApiClient
{
    public function fetch(string $url, ?string $token): array
    {
        $headers = "User-Agent: Corbidev-Repositories\r\n";
        if ($token) {
            $headers .= "Authorization: Bearer {$token}\r\n";
        }

        $context = stream_context_create([
            'http' => ['header' => $headers]
        ]);

        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            throw new RepositoryException('github_request_failed');
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            throw new RepositoryException('invalid_response');
        }

        return $decoded;
    }
}
