<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {

    if (!str_starts_with($class, 'Corbidev\\Repositories\\')) {
        return;
    }

    $relative = str_replace('Corbidev\\Repositories\\', '', $class);
    $relative = str_replace('\\', '/', $relative) . '.php';

    // Core
    $corePath = __DIR__ . '/' . $relative;

    // Admin (remove Admin/ prefix from path)
    $adminRelative = str_starts_with($relative, 'Admin/')
        ? substr($relative, strlen('Admin/'))
        : $relative;

    $adminPath = dirname(__DIR__) . '/admin/includes/' . $adminRelative;

    if (file_exists($corePath)) {
        require_once $corePath;
        return;
    }

    if (file_exists($adminPath)) {
        require_once $adminPath;
        return;
    }
});
