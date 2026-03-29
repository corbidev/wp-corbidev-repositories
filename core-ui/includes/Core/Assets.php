<?php

declare(strict_types=1);

namespace Corbidev\CoreUI\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class Assets
{
    private const HANDLE = 'corbidev-core-ui';

    private function __construct() {}

    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue'], 20);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue'], 20);

        /**
         * 🔥 FIX CRITIQUE : force type="module"
         */
        add_filter('script_loader_tag', [self::class, 'filterModuleScript'], 10, 3);
    }

    public static function enqueue(): void
    {
        if (!apply_filters('corbidev/core_ui/load', true)) {
            return;
        }

        if (!function_exists('corbidev_get_manifest')) {
            return;
        }

        $manifest = corbidev_get_manifest();
        $entry = $manifest['assets/src/core-ui/main.js'] ?? null;

        if (!$entry || empty($entry['file'])) {
            return;
        }

        $base_url = trailingslashit(\CDR_PLUGIN_URL) . 'assets/dist/';
        $js_handle = self::HANDLE . '-js';

        if (!wp_script_is($js_handle, 'enqueued')) {

            /**
             * 🔥 garantit disponibilité WP i18n
             */
            wp_enqueue_script('wp-i18n');

            /**
             * JS principal
             */
            wp_enqueue_script(
                $js_handle,
                esc_url($base_url . $entry['file']),
                ['wp-i18n'],
                self::version(),
                true
            );

            /**
             * 🔥 Traductions
             */
            wp_set_script_translations(
                $js_handle,
                'corbidevrepositories',
                \CDR_PLUGIN_DIR . 'languages'
            );

            /**
             * 🔥 Charger les chunks importés (robuste)
             */
            self::enqueueImports($manifest, $entry, $base_url);
        }

        /**
         * CSS
         */
        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $index => $css) {

                $css_handle = self::HANDLE . '-css-' . $index;

                if (wp_style_is($css_handle, 'enqueued')) {
                    continue;
                }

                wp_enqueue_style(
                    $css_handle,
                    esc_url($base_url . $css),
                    [],
                    self::version()
                );
            }
        }

        do_action('corbidev/core_ui/enqueued', self::HANDLE);
    }

    /**
     * 🔥 FIX MODULE (CRITIQUE)
     */
    public static function filterModuleScript(string $tag, string $handle, string $src): string
    {
        if (str_starts_with($handle, self::HANDLE)) {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        /**
         * 🔥 chunks Vite aussi
         */
        if (str_starts_with($handle, 'corbidev-chunk-')) {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        return $tag;
    }

    /**
     * 🔥 Gestion des imports Vite (chunks)
     */
    private static function enqueueImports(array $manifest, array $entry, string $base_url): void
    {
        if (empty($entry['imports'])) {
            return;
        }

        foreach ($entry['imports'] as $import) {

            if (!isset($manifest[$import]['file'])) {
                continue;
            }

            $handle = 'corbidev-chunk-' . md5($import);

            if (wp_script_is($handle, 'enqueued')) {
                continue;
            }

            wp_enqueue_script(
                $handle,
                esc_url($base_url . $manifest[$import]['file']),
                [],
                self::version(),
                true
            );
        }
    }

    private static function version(): string
    {
        return defined('CDR_VERSION')
            ? (string) \CDR_VERSION
            : (string) time();
    }
}