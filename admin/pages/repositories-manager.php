<?php

use Corbidev\Repositories\Repository\RepositoryStorage;

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

$repos = RepositoryStorage::getRepositories();
?>

<div class="wrap">

    <h1>Gestion des dépôts GitHub</h1>

    <h2>Ajouter un dépôt</h2>

    <form method="post">
        <input type="text" name="repo_name" placeholder="Nom du dépôt" required />
        <input type="text" name="repo_token" placeholder="Token (optionnel)" />
        <button class="button button-primary">Ajouter</button>
    </form>

    <hr>

    <h2>Liste des dépôts</h2>

    <table class="widefat striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Token</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($repos as $repo): ?>
                <tr>
                    <td><?php echo esc_html($repo['name']); ?></td>
                    <td><?php echo $repo['token'] ? '••••••••' : '-'; ?></td>
                    <td>
                        <?php if ($repo['name'] !== 'corbidev'): ?>
                            <a href="?page=corbidev-repositories&delete=<?php echo esc_attr($repo['name']); ?>" class="button button-danger">
                                Supprimer
                            </a>
                        <?php else: ?>
                            <em>Par défaut</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php
require CDR_PLUGIN_DIR . 'admin/pages/components/modal.php';