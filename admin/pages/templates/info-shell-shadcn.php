<?php if (!defined('ABSPATH')) exit; ?>

<div class="cdr-page-shell">
    <div class="cdr-page-stack">
        <section class="cdr-page-header">
            <span class="cdr-page-eyebrow"><?php echo esc_html__('Developer guide', 'corbidevrepositories'); ?></span>
            <h1 class="cdr-page-title"><?php echo esc_html__('Corbidev UI documentation', 'corbidevrepositories'); ?></h1>
            <p class="cdr-page-description">
                <?php echo esc_html__('Reference the UI bridge, its interaction patterns, and the documented extension points used by Corbidev admin pages.', 'corbidevrepositories'); ?>
            </p>
        </section>

        <nav class="cdr-tab-nav" aria-label="<?php echo esc_attr__('Documentation sections', 'corbidevrepositories'); ?>">
            <?php foreach ($tabs as $tab): ?>
                <?php $is_active = $current_tab && $current_tab['key'] === $tab['key']; ?>
                <a
                    href="<?php echo esc_url(admin_url('admin.php?page=corbidev-info&tab=' . $tab['key'])); ?>"
                    class="cdr-tab-link <?php echo $is_active ? 'cdr-tab-link-active' : ''; ?>"
                    data-tab="<?php echo esc_attr($tab['key']); ?>"
                >
                    <?php echo esc_html($tab['name']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <section class="cdr-card">
            <div class="cdr-card-body">
                <div class="cdr-docs">
                    <?php if ($template): ?>
                        <?php require $template; ?>
                    <?php else: ?>
                        <p><?php echo esc_html__('Template not found.', 'corbidevrepositories'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>
