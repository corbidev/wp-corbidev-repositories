<?php if (!defined('ABSPATH')) exit; ?>

<div class="cdr-page-shell">
    <div class="cdr-page-stack">
        <section class="cdr-page-header">
            <span class="cdr-page-eyebrow"><?php echo esc_html__('Diagnostics', 'corbidevrepositories'); ?></span>
            <h1 class="cdr-page-title"><?php echo esc_html__('Corbidev logs', 'corbidevrepositories'); ?></h1>
            <p class="cdr-page-description">
                <?php echo esc_html__('Review the latest plugin log output and clear it whenever you want a fresh troubleshooting session.', 'corbidevrepositories'); ?>
            </p>
        </section>

        <section class="cdr-card">
            <div class="cdr-card-header">
                <h2 class="cdr-card-title"><?php echo esc_html__('Runtime log', 'corbidevrepositories'); ?></h2>
            </div>
            <div class="cdr-card-body">
                <form class="cdr-actions" method="post">
                    <button class="cdr-btn cdr-btn-secondary" name="clear_logs" type="submit">
                        <?php echo esc_html__('Clear logs', 'corbidevrepositories'); ?>
                    </button>
                </form>

                <textarea class="cdr-log-output mt-4" readonly><?php echo esc_textarea($logs); ?></textarea>
            </div>
        </section>
    </div>
</div>
