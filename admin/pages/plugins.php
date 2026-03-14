<?php

use Corbidev\Repositories\Installer\PluginInstaller;

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">

<h1><?php echo esc_html__('Corbidev Plugins', 'corbidevrepositories'); ?></h1>

<table class="widefat striped">

<thead>
<tr>
<th><?php echo esc_html__('Plugin', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Description', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Version', 'corbidevrepositories'); ?></th>
<th><?php echo esc_html__('Action', 'corbidevrepositories'); ?></th>
</tr>
</thead>

<tbody>

<?php if (!empty($plugins)) : ?>

<?php foreach ($plugins as $plugin) : ?>

<tr>

<td>
<strong><?php echo esc_html($plugin['name']); ?></strong>
</td>

<td>
<?php echo esc_html($plugin['description'] ?? ''); ?>
</td>

<td>
<?php echo esc_html($plugin['version'] ?? ''); ?>
</td>

<td>

<?php

$repo = $plugin['name'];

if (!PluginInstaller::isInstalled($repo)) :

?>

<button
class="button button-primary corbidev-install"
data-repo="<?php echo esc_attr($repo); ?>"
>
<?php echo esc_html__('Install', 'corbidevrepositories'); ?>
</button>

<?php elseif (!PluginInstaller::isActive($repo)) : ?>

<button
class="button corbidev-activate"
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
<?php echo esc_html__('No plugins found.', 'corbidevrepositories'); ?>
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>
