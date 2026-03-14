<?php

namespace Corbidev\Repositories\Services;

use Corbidev\Repositories\Scanner\PluginScanner;

if (!defined('ABSPATH')) {
    exit;
}

class PluginService
{
    public static function list(): array
    {
        return PluginScanner::scan();
    }
}
