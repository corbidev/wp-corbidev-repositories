<?php

namespace Corbidev\Repositories\Manager;

if (!defined('ABSPATH')) {
    exit;
}

class RepositoryInstaller
{
    /**
     * Install plugin or theme from ZIP
     */
    public static function install(string $zipUrl, string $expectedSlug, string $type): bool
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $skin = new \Automatic_Upgrader_Skin();

        if ($type === 'plugin') {

            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            $upgrader = new \Plugin_Upgrader($skin);
            $result = $upgrader->install($zipUrl);

        } elseif ($type === 'theme') {

            require_once ABSPATH . 'wp-admin/includes/theme-install.php';

            $upgrader = new \Theme_Upgrader($skin);
            $result = $upgrader->install($zipUrl);

        } else {
            return false;
        }

        // Installation failed
        if (is_wp_error($result) || !$result) {
            return false;
        }

        // 🔥 Normalisation du dossier (fix GitHub zip)
        self::normalizeInstalledDirectory(
            $type,
            self::normalizeDirectorySlug($type, $expectedSlug)
        );

        return true;
    }

    /**
     * Rename GitHub extracted folder to proper slug
     */
    private static function normalizeInstalledDirectory(string $type, string $expectedSlug): ?string
    {
        global $wp_filesystem;

        if (!$wp_filesystem) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            \WP_Filesystem();
        }

        $baseDir = $type === 'plugin' ? WP_PLUGIN_DIR : get_theme_root();

        if (!is_dir($baseDir) || $expectedSlug === '') {
            return null;
        }

        $dirs = glob($baseDir . '/*', GLOB_ONLYDIR);

        if (!$dirs) {
            return null;
        }

        // On trie par date (plus récent en premier)
        usort($dirs, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        foreach ($dirs as $dir) {

            $basename = basename($dir);

            // On cible uniquement les dossiers proches du slug attendu
            if (strpos($basename, $expectedSlug) !== 0) {
                continue;
            }

            // Déjà bon nom
            if ($basename === $expectedSlug) {
                return $dir;
            }

            // Cas GitHub: repo-main / repo-1.2.3
            if (preg_match('/^' . preg_quote($expectedSlug, '/') . '[-_]/', $basename)) {

                $target = trailingslashit($baseDir) . $expectedSlug;

                // ⚠️ Supprime l'ancien si existe
                if ($wp_filesystem->is_dir($target)) {
                    $wp_filesystem->delete($target, true);
                }

                // Rename
                $moved = $wp_filesystem->move($dir, $target);

                if ($moved) {
                    return $target;
                }

                return null;
            }
        }

        return null;
    }

    private static function normalizeDirectorySlug(string $type, string $expectedSlug): string
    {
        $normalized = trim(str_replace('\\', '/', $expectedSlug), '/');

        if ($type === 'plugin') {
            $directory = dirname($normalized);

            if ($directory !== '.' && $directory !== DIRECTORY_SEPARATOR) {
                $normalized = $directory;
            }
        }

        return basename($normalized);
    }
}
