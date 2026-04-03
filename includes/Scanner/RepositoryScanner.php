<?php

namespace Corbidev\Repositories\Scanner;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryScanner
{
    private ?array $plugins = null;

    /**
     * Scan plugin or theme
     */
    public function scan(string $name, string $type): array
    {
        if ($type === 'plugin') {
            return $this->scanPlugin($name);
        }

        if ($type === 'theme') {
            return $this->scanTheme($name);
        }

        return $this->empty();
    }

    /**
     * Scan plugins (WordPress native)
     */
    private function scanPlugin(string $name): array
    {
        if ($this->plugins === null) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
            $this->plugins = get_plugins();
        }

        foreach ($this->plugins as $pluginFile => $data) {

            // match sur nom plugin OU text domain OU dossier
            if (
                str_contains(strtolower($pluginFile), strtolower($name)) ||
                str_contains(strtolower($data['Name'] ?? ''), strtolower($name)) ||
                str_contains(strtolower($data['TextDomain'] ?? ''), strtolower($name))
            ) {
                return [
                    'installed' => true,
                    'active'    => is_plugin_active($pluginFile) || is_plugin_active_for_network($pluginFile),
                    'version'   => $data['Version'] ?? null,
                    'slug'      => $pluginFile,
                ];
            }
        }

        return $this->empty();
    }

    /**
     * Scan theme (WordPress native)
     */
    private function scanTheme(string $name): array
    {
        $theme = wp_get_theme($name);

        if ($theme->exists()) {
            return [
                'installed' => true,
                'active'    => get_stylesheet() === $name,
                'version'   => $theme->get('Version'),
                'slug'      => $name,
            ];
        }

        // fallback scan (si nom != slug)
        $themes = wp_get_themes();

        foreach ($themes as $slug => $theme) {

            if (
                str_contains(strtolower($slug), strtolower($name)) ||
                str_contains(strtolower($theme->get('Name')), strtolower($name))
            ) {
                return [
                    'installed' => true,
                    'active'    => get_stylesheet() === $slug,
                    'version'   => $theme->get('Version'),
                    'slug'      => $slug,
                ];
            }
        }

        return $this->empty();
    }

    /**
     * Default empty result
     */
    private function empty(): array
    {
        return [
            'installed' => false,
            'active'    => false,
            'version'   => null,
            'slug'      => null,
        ];
    }
}
