<?php

namespace Corbidev\Repositories\Updates;

use Corbidev\Repositories\Repository\RepositoryManager;
use Corbidev\Repositories\Services\RepositoryService;

if (!defined('ABSPATH')) {
    exit;
}

class WordPressUpdateBridge
{
    private static ?array $pluginUpdates = null;
    private static ?array $themeUpdates = null;

    public static function register(): void
    {
        add_filter('site_transient_update_plugins', [self::class, 'filterPluginUpdates']);
        add_filter('site_transient_update_themes', [self::class, 'filterThemeUpdates']);
    }

    public static function filterPluginUpdates($transient)
    {
        if (!is_object($transient)) {
            $transient = new \stdClass();
        }

        $transient->response = is_array($transient->response ?? null) ? $transient->response : [];
        $transient->no_update = is_array($transient->no_update ?? null) ? $transient->no_update : [];

        foreach (self::getPluginUpdates() as $pluginFile => $update) {
            $transient->response[$pluginFile] = (object) [
                'id'            => sprintf('corbidev:%s/%s', $update['owner'], $update['repo']),
                'slug'          => self::pluginSlug($pluginFile, $update['repo']),
                'plugin'        => $pluginFile,
                'new_version'   => $update['version'],
                'url'           => $update['url'],
                'package'       => $update['package'],
                'icons'         => [],
                'banners'       => [],
                'banners_rtl'   => [],
                'tested'        => '',
                'requires_php'  => '',
                'compatibility' => new \stdClass(),
            ];

            unset($transient->no_update[$pluginFile]);
        }

        return $transient;
    }

    public static function filterThemeUpdates($transient)
    {
        if (!is_object($transient)) {
            $transient = new \stdClass();
        }

        $transient->response = is_array($transient->response ?? null) ? $transient->response : [];
        $transient->no_update = is_array($transient->no_update ?? null) ? $transient->no_update : [];

        foreach (self::getThemeUpdates() as $stylesheet => $update) {
            $transient->response[$stylesheet] = [
                'theme'        => $stylesheet,
                'new_version'  => $update['version'],
                'url'          => $update['url'],
                'package'      => $update['package'],
                'requires'     => '',
                'requires_php' => '',
            ];

            unset($transient->no_update[$stylesheet]);
        }

        return $transient;
    }

    private static function getPluginUpdates(): array
    {
        if (self::$pluginUpdates !== null) {
            return self::$pluginUpdates;
        }

        self::$pluginUpdates = self::buildUpdates('plugin');

        return self::$pluginUpdates;
    }

    private static function getThemeUpdates(): array
    {
        if (self::$themeUpdates !== null) {
            return self::$themeUpdates;
        }

        self::$themeUpdates = self::buildUpdates('theme');

        return self::$themeUpdates;
    }

    private static function buildUpdates(string $type): array
    {
        $service = new RepositoryService();

        try {
            $items = $service->getAll(null, $type);
        } catch (\Throwable $e) {
            return [];
        }

        $updates = [];

        foreach ($items as $item) {
            if (empty($item['is_installed']) || empty($item['has_update'])) {
                continue;
            }

            $slug = (string) ($item['slug'] ?? '');
            $owner = (string) ($item['owner'] ?? '');
            $repo = (string) ($item['name'] ?? '');
            $version = (string) ($item['version'] ?? '');

            if ($slug === '' || $owner === '' || $repo === '' || $version === '') {
                continue;
            }

            $package = self::resolvePackageUrl($owner, $repo);

            if ($package === null) {
                continue;
            }

            $updates[$slug] = [
                'owner'   => $owner,
                'repo'    => $repo,
                'version' => self::formatWordPressVersion($version),
                'url'     => sprintf('https://github.com/%s/%s', $owner, $repo),
                'package' => $package,
            ];
        }

        return $updates;
    }

    private static function resolvePackageUrl(string $owner, string $repo): ?string
    {
        $repository = RepositoryManager::get($owner);

        if (!$repository || empty($repository['client'])) {
            return null;
        }

        try {
            return $repository['client']->getZipUrl($owner, $repo);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function pluginSlug(string $pluginFile, string $fallback): string
    {
        $slug = dirname($pluginFile);

        if ($slug === '.' || $slug === DIRECTORY_SEPARATOR) {
            return $fallback;
        }

        return $slug;
    }

    private static function formatWordPressVersion(string $version): string
    {
        $normalized = ltrim(trim($version), 'vV');

        return $normalized !== '' ? $normalized : $version;
    }
}
