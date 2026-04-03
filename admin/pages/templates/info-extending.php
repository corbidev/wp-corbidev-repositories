<?php if (!defined('ABSPATH')) exit; ?>

<p>
    <?php echo esc_html__('Corbidev UI allows external plugins to extend the developer documentation by adding custom tabs.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2>🧩 <?php echo esc_html__('Add a custom tab', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('Use the WordPress filter to register your tab:', 'corbidevrepositories'); ?>
</p>

<pre><code>add_filter('corbidev/info_tabs', function ($tabs) {

    $tabs[] = [
        'priority' => 10, // 🔥 order (lower = earlier)
        'key'      => 'my-plugin', // unique identifier
        'name'     => 'My Plugin',

        // 🔥 REQUIRED for external plugins
        'path'     => plugin_dir_path(__FILE__) . 'admin/pages/info-my-plugin.php',
    ];

    return $tabs;

});</code></pre>

<hr>

<h2>📁 <?php echo esc_html__('Template location', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('Your template can be located anywhere inside your plugin.', 'corbidevrepositories'); ?>
</p>

<pre><code>/your-plugin/admin/pages/info-my-plugin.php</code></pre>

<hr>

<h2>🧠 <?php echo esc_html__('Understanding priority', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('Lower priority loads first (e.g. 1 before 10)', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Tabs with the same priority are sorted by their key (stable order)', 'corbidevrepositories'); ?>
    </li>
    <li><?php echo esc_html__('Internal tabs are always displayed before external tabs', 'corbidevrepositories'); ?>
    </li>
</ul>

<hr>

<h2>⚠️ <?php echo esc_html__('Rules', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('The template file must exist', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Do not use inline JavaScript', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use data-ui attributes for interactions', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Always escape output using WordPress functions', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Ensure your key is unique', 'corbidevrepositories'); ?></li>
</ul>

<hr>