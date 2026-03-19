<?php

namespace Corbidev\Repositories\Cache;

if (!defined('ABSPATH')) {
    exit;
}

class GithubCache
{
    private const GROUP = 'cdr_github';
    private const DEFAULT_TTL = 300; // 5 minutes

    /**
     * Génère une clé propre
     */
    private static function key(string $key): string
    {
        return 'cdr_' . md5($key);
    }

    /**
     * Récupère une valeur du cache
     */
    public static function get(string $key)
    {
        return get_transient(self::key($key));
    }

    /**
     * Stocke une valeur
     */
    public static function set(string $key, $value, int $ttl = self::DEFAULT_TTL): void
    {
        set_transient(self::key($key), $value, $ttl);
    }

    /**
     * Supprime une entrée
     */
    public static function delete(string $key): void
    {
        delete_transient(self::key($key));
    }

    /**
     * Cache avec callback (pattern recommandé)
     */
    public static function remember(string $key, callable $callback, int $ttl = self::DEFAULT_TTL)
    {
        $cached = self::get($key);

        if ($cached !== false) {
            return $cached;
        }

        $value = $callback();

        self::set($key, $value, $ttl);

        return $value;
    }

    /**
     * Flush complet (optionnel)
     */
    public static function flush(): void
    {
        global $wpdb;

        $wpdb->query(
            "DELETE FROM {$wpdb->options} 
             WHERE option_name LIKE '_transient_cdr_%' 
             OR option_name LIKE '_transient_timeout_cdr_%'"
        );
    }
}
