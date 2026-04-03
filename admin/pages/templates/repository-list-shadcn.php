<?php
if (!defined('ABSPATH')) exit;

/** @var string $type */
/** @var array $items */
/** @var array $errors */

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
                <div class="cdr-section-stack">
                    <?php if (!empty($errors)): ?>
                        <div class="cdr-alert cdr-alert-error" role="alert">
                            <div class="cdr-alert-icon" aria-hidden="true">!</div>
                            <div class="cdr-alert-content">
                                <p class="cdr-alert-title">
                                    <?php echo esc_html__('GitHub access issue', 'corbidevrepositories'); ?>
                                </p>
                                <div class="cdr-alert-list">
                                    <?php foreach ($errors as $error): ?>
                                        <?php
                                        $error_owner = (string) ($error['owner'] ?? '');
                                        $error_reason = (string) ($error['reason'] ?? '');

                                        if ($error_reason === 'rate_limit') {
                                            $message = sprintf(
                                                __('The GitHub repository owner "%s" can no longer access the GitHub API right now because the rate limit has been reached.', 'corbidevrepositories'),
                                                $error_owner
                                            );
                                        } else {
                                            $message = sprintf(
                                                __('The GitHub repository owner "%s" is currently unavailable through the GitHub API.', 'corbidevrepositories'),
                                                $error_owner
                                            );
                                        }
                                        ?>
                                        <p class="cdr-alert-message"><?php echo esc_html($message); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($items)): ?>
                        <div class="cdr-empty">
                            <p class="cdr-empty-title">
                                <?php echo esc_html__('No packages available', 'corbidevrepositories'); ?>
                            </p>
                            <p class="cdr-empty-description">
                                <?php
                                echo !empty($errors)
                                    ? esc_html__('Packages will appear here again once GitHub access is restored for the configured repository owners.', 'corbidevrepositories')
                                    : esc_html__('No items found for the current repositories.', 'corbidevrepositories');
                                ?>
                            </p>
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
            </div>
        </section>
    </div>
</div>
