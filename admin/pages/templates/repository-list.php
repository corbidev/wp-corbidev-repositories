<?php
if (!defined('ABSPATH')) exit;

/** @var string $type */
/** @var array $items */
?>

<div class="wrap">

    <h1>
        <?php
        printf(
            'Corbidev %s',
            esc_html(
                $type === 'theme'
                    ? esc_html__('Themes', 'corbidevrepositories')
                    : esc_html__('Plugins', 'corbidevrepositories')
            )
        );
        ?>
    </h1>

    <?php if (empty($items)): ?>

        <p><?php echo esc_html__('No items found.', 'corbidevrepositories'); ?></p>

    <?php else: ?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Name', 'corbidevrepositories'); ?></th>
                    <th><?php echo esc_html__('Description', 'corbidevrepositories'); ?></th>
                    <th><?php echo esc_html__('Version', 'corbidevrepositories'); ?></th>
                    <th><?php echo esc_html__('Action', 'corbidevrepositories'); ?></th>
                </tr>
            </thead>

            <tbody>

<?php foreach ($items as $item): 

 include __DIR__ . '/repository-item.php'; 

endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>
