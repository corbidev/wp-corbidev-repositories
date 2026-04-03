<?php
if (!defined('ABSPATH')) exit;

/** @var array $item */
/** @var string $type */

$isInstalled = $item['is_installed'] ?? false;
$isActive = $item['is_active'] ?? false;
$hasUpdate = $item['has_update'] ?? false;
$description = trim((string) ($item['description'] ?? ''));
$slug = (string) ($item['slug'] ?? '');
$name = (string) ($item['name'] ?? '');
$owner = (string) ($item['owner'] ?? '');
$version = (string) ($item['version'] ?? '-');
$installedVersion = $item['installed_version'] ?? null;
?>

<tr
    data-item-name="<?= esc_attr($name) ?>"
    data-item-owner="<?= esc_attr($owner) ?>"
    data-item-type="<?= esc_attr($type) ?>"
    data-item-slug="<?= esc_attr($slug) ?>"
    data-item-installed="<?= $isInstalled ? '1' : '0' ?>"
    data-item-active="<?= $isActive ? '1' : '0' ?>"
    data-item-has-update="<?= $hasUpdate ? '1' : '0' ?>"
>
    <td>
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-medium text-slate-950"><?= esc_html($name) ?></span>
                <span class="cdr-inline-meta"><?= esc_html($owner) ?></span>
            </div>
            <p class="max-w-xl text-sm leading-6 text-slate-600">
                <?= esc_html($description !== '' ? $description : __('No description available.', 'corbidevrepositories')) ?>
            </p>
        </div>
    </td>
    <td data-role="status">
        <div class="cdr-actions">
            <?php if (!$isInstalled): ?>
                <span class="cdr-badge cdr-badge-neutral">
                    <span class="cdr-status-dot bg-slate-400"></span>
                    <?= esc_html__('Not installed', 'corbidevrepositories') ?>
                </span>
            <?php elseif ($type === 'plugin' && $isActive): ?>
                <span class="cdr-badge cdr-badge-success">
                    <span class="cdr-status-dot bg-emerald-500"></span>
                    <?= esc_html__('Active', 'corbidevrepositories') ?>
                </span>
            <?php elseif ($type === 'plugin'): ?>
                <span class="cdr-badge cdr-badge-warning">
                    <span class="cdr-status-dot bg-amber-500"></span>
                    <?= esc_html__('Installed, inactive', 'corbidevrepositories') ?>
                </span>
            <?php else: ?>
                <span class="cdr-badge cdr-badge-success">
                    <span class="cdr-status-dot bg-emerald-500"></span>
                    <?= esc_html__('Installed', 'corbidevrepositories') ?>
                </span>
            <?php endif; ?>

            <?php if ($hasUpdate): ?>
                <span class="cdr-badge cdr-badge-warning">
                    <span class="cdr-status-dot bg-amber-500"></span>
                    <?= esc_html__('Update available', 'corbidevrepositories') ?>
                </span>
            <?php endif; ?>
        </div>
    </td>
    <td>
        <div class="space-y-2">
            <div class="font-medium text-slate-950"><?= esc_html($version) ?></div>
            <?php if (!empty($installedVersion)): ?>
                <div class="text-xs uppercase tracking-[0.18em] text-slate-500">
                    <?= esc_html__('Installed', 'corbidevrepositories') ?>: <?= esc_html($installedVersion) ?>
                </div>
            <?php endif; ?>
        </div>
    </td>
    <td data-role="actions">
        <div class="cdr-actions">
            <?php if (!$isInstalled): ?>
                <button
                    class="cdr-btn cdr-btn-primary"
                    data-action="install"
                    data-name="<?= esc_attr($name) ?>"
                    data-owner="<?= esc_attr($owner) ?>"
                    data-type="<?= esc_attr($type) ?>"
                    type="button"
                >
                    <?= esc_html__('Install', 'corbidevrepositories') ?>
                </button>
            <?php else: ?>
                <?php if ($type === 'plugin'): ?>
                    <?php if (!$isActive): ?>
                        <button class="cdr-btn cdr-btn-primary" data-action="activate" data-name="<?= esc_attr($slug) ?>" type="button">
                            <?= esc_html__('Activate', 'corbidevrepositories') ?>
                        </button>
                    <?php else: ?>
                        <button class="cdr-btn cdr-btn-secondary" data-action="deactivate" data-name="<?= esc_attr($slug) ?>" type="button">
                            <?= esc_html__('Deactivate', 'corbidevrepositories') ?>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($hasUpdate): ?>
                    <button
                        class="cdr-btn cdr-btn-outline"
                        data-action="update"
                        data-name="<?= esc_attr($name) ?>"
                        data-owner="<?= esc_attr($owner) ?>"
                        data-type="<?= esc_attr($type) ?>"
                        type="button"
                    >
                        <?= esc_html__('Update', 'corbidevrepositories') ?>
                    </button>
                <?php endif; ?>

                <button class="cdr-btn cdr-btn-danger" data-action="delete" data-name="<?= esc_attr($slug) ?>" data-type="<?= esc_attr($type) ?>" type="button">
                    <?= esc_html__('Delete', 'corbidevrepositories') ?>
                </button>
            <?php endif; ?>
        </div>
    </td>
</tr>
