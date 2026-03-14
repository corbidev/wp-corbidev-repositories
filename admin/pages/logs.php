<?php
use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

$file = WP_CONTENT_DIR . '/corbidev-logs/repositories.log';
$logs = file_exists($file) ? file_get_contents($file) : '';

?>
<div class="wrap">
<h1>Corbidev Logs</h1>
<textarea style="width:100%;height:500px;font-family:monospace"><?php echo esc_textarea($logs); ?></textarea>
</div>
