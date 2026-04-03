<?php
if (!defined('ABSPATH')) exit;

/** @var string $type */
/** @var array $items */

$page_title = $type === 'theme'
    ? esc_html__('Corbidev themes', 'corbidevrepositories')
    : esc_html__('Corbidev plugins', 'corbidevrepositories');

$page_eyebrow = $type === 'theme'
    ? esc_html__('Themes', 'corbidevrepositories')
    : esc_html__('Plugins', 'corbidevrepositories');
?>

<div class="cdr-page-shell">
    <div class="cdr-page-stack">
        <section class="cdr-page-header">
            <span class="cdr-page-eyebrow"><?php echo $page_eyebrow; ?></span>
            <h1 class="cdr-page-title"><?php echo esc_html($page_title); ?></h1>
            <p class="cdr-page-description">
                <?php echo esc_html__('Install and maintain packages discovered from the repositories configured in the plugin.', 'corbidevrepositories'); ?>
            </p>
        </section>

        <section class="cdr-card">
            <div class="cdr-card-header">
                <h2 class="cdr-card-title"><?php echo esc_html__('Available packages', 'corbidevrepositories'); ?></h2>
                <p class="cdr-card-description">
                    <?php echo esc_html__('Each action below is wired to the Corbidev UI bridge and runs without leaving the page flow.', 'corbidevrepositories'); ?>
                </p>
            </div>
            <div class="cdr-card-body">
                <?php if (empty($items)): ?>
                    <div class="cdr-empty">
                        <?php echo esc_html__('No items found for the current repositories.', 'corbidevrepositories'); ?>
                    </div>
                <?php else: ?>
                    <div class="cdr-table-wrap">
                        <table class="cdr-table">
                            <thead>
                                <tr>
                                    <th><?php echo esc_html__('Package', 'corbidevrepositories'); ?></th>
                                    <th><?php echo esc_html__('Status', 'corbidevrepositories'); ?></th>
                                    <th><?php echo esc_html__('Version', 'corbidevrepositories'); ?></th>
                                    <th><?php echo esc_html__('Actions', 'corbidevrepositories'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <?php include __DIR__ . '/repository-item-shadcn.php'; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
