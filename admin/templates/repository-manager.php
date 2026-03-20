<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1>Gestion des dépôts</h1>

    <form id="cdr-repo-form">
        <input type="text" name="name" placeholder="Nom du dépôt (ex: corbidev)" required>
        <input type="text" name="token" placeholder="Token GitHub (optionnel)">
        <button class="button button-primary">Ajouter</button>
    </form>

    <hr>

    <table class="widefat">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Token</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($repositories as $repo): ?>
            <tr>
                <td><?= esc_html($repo['name']) ?></td>
                <td>••••••••</td>
                <td>
                    <button class="button cdr-delete" data-name="<?= esc_attr($repo['name']) ?>">
                        Supprimer
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>