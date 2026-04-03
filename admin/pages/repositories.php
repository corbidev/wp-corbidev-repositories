<?php

if (!defined('ABSPATH')) {
    exit;
}

// Variables attendues
// $items
// $type

$template = __DIR__ . '/templates/repository-list-shadcn.php';

if (file_exists($template)) {
    require $template;
}
