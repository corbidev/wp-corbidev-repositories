<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once CDR_PLUGIN_DIR . 'includes/autoload.php';

add_action('plugins_loaded', function () {
    \Corbidev\Repositories\Core\Plugin::init();
});
