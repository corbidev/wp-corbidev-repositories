<?php
if (!defined('ABSPATH')) exit;

/** @var string $type */
/** @var array $items */

$title = $type === 'plugin' ? 'Plugins' : 'Thèmes';
?>

<div class="cdr-container">
    <h1><?php echo esc_html($title); ?> Corbidev</h1>

    <div class="cdr-list" data-type="<?php echo esc_attr($type); ?>">
        <?php foreach ($items as $item): ?>
            <?php include __DIR__ . '/repository-item.php'; ?>
        <?php endforeach; ?>
    </div>
</div>