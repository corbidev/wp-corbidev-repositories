<?php
namespace Corbidev\Repositories\Services;

if (!defined('ABSPATH')) {
    exit;
}

class Logger
{
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const DEBUG = 'DEBUG';

    private static function file(): string
    {
        $dir = WP_CONTENT_DIR . '/corbidev-logs';

        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }

        return $dir . '/repositories.log';
    }

    public static function write(string $level, $message): void
    {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }

        $line = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), $level, $message);

        file_put_contents(self::file(), $line, FILE_APPEND);
    }

    public static function info($message): void
    {
        self::write(self::INFO, $message);
    }

    public static function error($message): void
    {
        self::write(self::ERROR, $message);
    }

    public static function debug($message): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            self::write(self::DEBUG, $message);
        }
    }
}
