<?php

declare(strict_types=1);

namespace Corbidev\CoreUI\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class Renderer
{
    private const TEMPLATE_PATH = 'core-ui/templates/';

    private function __construct() {}

    /**
     * Render a template safely
     */
    public static function render(string $template, array $data = []): void
    {
        $file = self::locate_template($template);

        if (!$file) {
            return;
        }

        if (!empty($data)) {
            extract($data, EXTR_SKIP);
        }

        include $file;
    }

    /**
     * Locate template (theme override → plugin fallback)
     */
    private static function locate_template(string $template): ?string
    {
        $template = sanitize_file_name($template) . '.php';

        /**
         * =========================
         * 1. Theme override
         * =========================
         */
        $theme_path = trailingslashit(get_stylesheet_directory()) . self::TEMPLATE_PATH . $template;

        if (file_exists($theme_path)) {
            return $theme_path;
        }

        /**
         * =========================
         * 2. Plugin fallback
         * =========================
         */
        $plugin_path = trailingslashit(\CDR_PLUGIN_DIR) . self::TEMPLATE_PATH . $template;

        if (file_exists($plugin_path)) {
            return $plugin_path;
        }

        return null;
    }

    /**
     * Render modal container (once)
     */
    public static function render_modal_container(): void
    {
        static $rendered = false;

        if ($rendered) {
            return;
        }

        $rendered = true;

        self::render('modal');
    }

    /**
     * Render banner container (once)
     */
    public static function render_banner_container(): void
    {
        static $rendered = false;

        if ($rendered) {
            return;
        }

        $rendered = true;

        self::render('banner');
    }

    /**
     * Render all UI containers
     */
    public static function render_all(): void
    {
        self::render_modal_container();
        self::render_banner_container();
    }

    /**
     * Register hooks
     */
    public static function register_hooks(): void
    {
        add_action('wp_footer', [self::class, 'render_all'], 100);
        add_action('admin_footer', [self::class, 'render_all'], 100);
    }
}