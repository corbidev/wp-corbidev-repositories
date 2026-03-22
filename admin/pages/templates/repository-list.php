<?php
if (!defined('ABSPATH')) exit;

/** @var string $type */
/** @var array $items */

$title = $type === 'plugin' ? 'Plugins' : 'Thèmes';
?>



<div class="wrap">

    <h1>
        Corbidev <?= esc_html($type === 'theme' ? 'Themes' : 'Plugins') ?>
    </h1>

    <?php if (empty($items)): ?>

        <p>Aucun élément trouvé.</p>

    <?php else: ?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Version</th>
                    <th>Action</th>
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

