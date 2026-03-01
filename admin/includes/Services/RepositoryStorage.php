<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Admin\Services;

final class RepositoryStorage
{
    private string $optionKey = 'corbidev_repositories';

    public function get(): array
    {
        if (is_multisite()) {
            return get_site_option($this->optionKey, []);
        }
        return get_option($this->optionKey, []);
    }

    public function save(array $data): void
    {
        if (is_multisite()) {
            update_site_option($this->optionKey, $data);
            return;
        }
        update_option($this->optionKey, $data);
    }
}
