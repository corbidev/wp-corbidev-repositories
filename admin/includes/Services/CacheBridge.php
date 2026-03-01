<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Admin\Services;

final class CacheBridge
{
    public function get(string $key): mixed
    {
        return get_transient($key);
    }

    public function set(string $key, mixed $value, int $ttl): void
    {
        set_transient($key, $value, $ttl);
    }
}
