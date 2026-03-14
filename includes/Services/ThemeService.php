<?php

namespace Corbidev\Repositories\Services;

use Corbidev\Repositories\Scanner\ThemeScanner;

if (!defined('ABSPATH')) {
    exit;
}

class ThemeService
{
    public static function list(): array
    {
        return ThemeScanner::scan();
    }
}
