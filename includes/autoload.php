
<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'Corbidev\\Repositories')) {
        $relative = str_replace('Corbidev\\Repositories\\', '', $class);
        $relative = str_replace('\\', '/', $relative);
        $file = __DIR__ . '/' . $relative . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
