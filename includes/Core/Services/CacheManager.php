<?php
declare(strict_types=1);
namespace Corbidev\Repositories\Core\Services;
final class CacheManager {
    private array $cache = [];
    public function get(string $key): mixed { return $this->cache[$key] ?? null; }
    public function set(string $key, mixed $value, int $ttl): void { $this->cache[$key] = $value; }
}
