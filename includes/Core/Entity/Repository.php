
<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Core\Entity;

final class Repository
{
    public function __construct(
        private string $owner,
        private string $name,
        private ?string $token,
        private string $apiBaseUrl
    ) {}

    public function owner(): string { return $this->owner; }
    public function name(): string { return $this->name; }
    public function token(): ?string { return $this->token; }
    public function apiBaseUrl(): string { return $this->apiBaseUrl; }
}
