<?php
if (!defined('ABSPATH')) exit;

/** @var array $item */
/** @var string $type */
?>

<div class="cdr-item">
    <h3><?php echo esc_html($item['name']); ?></h3>

    <p><?php echo esc_html($item['description']); ?></p>

    <span>Version: <?php echo esc_html($item['version'] ?? 'N/A'); ?></span>

    <button 
        class="button button-primary"
        data-action="install"
        data-type="<?php echo esc_attr($type); ?>"
        data-owner="corbidev"
        data-name="<?php echo esc_attr($item['slug']); ?>"
    >
        Installer
    </button>
</div>