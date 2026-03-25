<?php
if (!defined('ABSPATH')) exit;

/** @var array $item */
/** @var string $type */

$isInstalled = $item['is_installed'] ?? false;
$isActive    = $item['is_active'] ?? false;
$hasUpdate   = $item['has_update'] ?? false;
?>

<tr>
    <td><?= esc_html($item['name']) ?></td>

    <td><?= esc_html($item['description'] ?? '') ?></td>

    <td>
        <?= esc_html($item['version']) ?>

        <?php if (!empty($item['installed_version'])): ?>
            <br>
            <small>
                <?= esc_html__('Installed:', 'corbidevrepositories') ?>
                <?= esc_html($item['installed_version']) ?>
            </small>
        <?php endif; ?>
    </td>

    <td>

        <?php if (!$isInstalled): ?>

            <button data-action="install"
                data-name="<?= esc_attr($item['name']) ?>"
                data-owner="<?= esc_attr($item['owner']) ?>"
                data-type="<?= esc_attr($type) ?>">
                <?= esc_html__('Install', 'corbidevrepositories') ?>
            </button>

        <?php else: ?>

            <?php if ($type === 'plugin'): ?>

                <?php if (!$isActive): ?>
                    <button data-action="activate"
                        data-name="<?= esc_attr($item['slug']) ?>">
                        <?= esc_html__('Activate', 'corbidevrepositories') ?>
                    </button>
                <?php else: ?>
                    <button data-action="deactivate"
                        data-name="<?= esc_attr($item['slug']) ?>">
                        <?= esc_html__('Deactivate', 'corbidevrepositories') ?>
                    </button>
                <?php endif; ?>

            <?php endif; ?>

            <?php if ($hasUpdate): ?>
                <button data-action="update"
                    data-name="<?= esc_attr($item['name']) ?>"
                    data-owner="<?= esc_attr($item['owner']) ?>"
                    data-type="<?= esc_attr($type) ?>">
                    <?= esc_html__('Update', 'corbidevrepositories') ?>
                </button>
            <?php endif; ?>

            <button data-action="delete"
                data-name="<?= esc_attr($item['slug']) ?>"
                data-type="<?= esc_attr($type) ?>">
                <?= esc_html__('Delete', 'corbidevrepositories') ?>
            </button>

        <?php endif; ?>

    </td>
</tr>
