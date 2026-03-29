<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {

    /*
     * =========================
     * REPOSITORIES (EXISTANT)
     * =========================
     */
    if (str_starts_with($class, 'Corbidev\\Repositories\\')) {

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

        return;
    }

    /*
     * =========================
     * CORE UI (NOUVEAU)
     * =========================
     */
    if (str_starts_with($class, 'Corbidev\\CoreUI\\')) {

        $relative = str_replace('Corbidev\\CoreUI\\', '', $class);
        $relative = str_replace('\\', '/', $relative) . '.php';

        $coreUiPath = dirname(__DIR__) . '/core-ui/includes/' . $relative;

        if (file_exists($coreUiPath)) {
            require_once $coreUiPath;
            return;
        }

        return;
    }
});
