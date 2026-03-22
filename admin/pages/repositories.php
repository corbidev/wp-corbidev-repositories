<?php

if (!defined('ABSPATH')) {
    exit;
}

// IMPORTANT : composant global à conserver
require CDR_PLUGIN_DIR . 'admin/pages/components/modal.php';

// Variables attendues
// $items
// $type

$template = __DIR__ . '/templates/repository-list.php';

if (file_exists($template)) {
    require $template;
}
