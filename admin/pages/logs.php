<?php

use Corbidev\Repositories\Services\Logger;

if (!defined('ABSPATH')) {
    exit;
}

if (isset($_POST['clear_logs'])) {
    Logger::clear();
}

$logs = Logger::read();

?>

<?php
require __DIR__ . '/templates/logs-shadcn.php';
return;
?>

<div class="wrap">

<h1>Corbidev Logs</h1>

<form method="post">
    <button class="button button-secondary" name="clear_logs">
        Clear logs
    </button>
</form>

<textarea
style="width:100%;height:500px;margin-top:20px;font-family:monospace"
readonly
><?php echo esc_textarea($logs); ?></textarea>

</div>
