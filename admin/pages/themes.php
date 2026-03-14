<?php

use Corbidev\Repositories\Installer\ThemeInstaller;

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">

<h1><?php echo esc_html__('Corbidev Themes', 'corbidevrepositories'); ?></h1>

<table class="widefat striped">

<thead>
<tr>
<th><?php echo esc_html__('Theme', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Description', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Version', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Action', 'corbidevrepositories'); ?></th>
</tr>
</thead>

<tbody>

<?php if (!empty($themes)) : ?>

<?php foreach ($themes as $theme) : ?>

<tr>

<td>
<strong><?php echo esc_html($theme['name']); ?></strong>
</td>

<td>
<?php echo esc_html($theme['description'] ?? ''); ?>
</td>

<td>
<?php echo esc_html($theme['version'] ?? ''); ?>
</td>

<td>

<?php

$repo = $theme['name'];

if (!ThemeInstaller::isInstalled($repo)) :

?>

<button
class="button button-primary corbidev-install-theme"
data-repo="<?php echo esc_attr($repo); ?>"
>
<?php echo esc_html__('Install', 'corbidevrepositories'); ?>
</button>

<?php elseif (!ThemeInstaller::isActive($repo)) : ?>

<button
class="button corbidev-activate-theme"
data-repo="<?php echo esc_attr($repo); ?>"
>
<?php echo esc_html__('Activate', 'corbidevrepositories'); ?>
</button>

<?php else : ?>

<span class="button disabled">
<?php echo esc_html__('Active', 'corbidevrepositories'); ?>
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else : ?>

<tr>
<td colspan="4">
<?php echo esc_html__('No themes found.', 'corbidevrepositories'); ?>
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>
