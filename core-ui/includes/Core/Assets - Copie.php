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

        $dist_url = trailingslashit(\CDR_PLUGIN_URL) . 'assets/dist/';

        /**
         * =========================
         * JS
         * =========================
         */
        $js_handle = self::HANDLE . '-js';

        if (!wp_script_is($js_handle, 'enqueued')) {

            /**
             * 🔥 IMPORTANT :
             * Toujours enregistrer wp-i18n AVANT (sécurité modules ES)
             */
            wp_enqueue_script('wp-i18n');

            wp_enqueue_script(
                $js_handle,
                esc_url($dist_url . $entry['file']),
                ['wp-i18n'], // ✅ nécessaire pour admin + compat plugins
                self::version(),
                true
            );

            /**
             * 🔥 ES MODULE
             */
            wp_script_add_data($js_handle, 'type', 'module');

            /**
             * 🔥 Traductions globales core-ui
             */
            wp_set_script_translations(
                $js_handle,
                'corbidevrepositories',
                \CDR_PLUGIN_DIR . 'languages'
            );
        }

        /**
         * =========================
         * CSS
         * =========================
         */
        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $index => $css) {

                $css_handle = self::HANDLE . '-css-' . $index;

                if (wp_style_is($css_handle, 'enqueued')) {
                    continue;
                }

                wp_enqueue_style(
                    $css_handle,
                    esc_url($dist_url . $css),
                    [],
                    self::version()
                );
            }
        }

        /**
         * =========================
         * Expose CoreUI global
         * =========================
         */
        do_action('corbidev/core_ui/enqueued', self::HANDLE);
    }

    private static function version(): string
    {
        return defined('CDR_VERSION')
            ? (string) \CDR_VERSION
            : (string) time();
    }
}