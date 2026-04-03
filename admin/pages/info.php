<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tabs configuration
 */
$tabs = [
    [
        'priority' => 1,
        'key'      => 'ui',
        'name'     => __('UI (Modal & Banner)', 'corbidevrepositories'),
        'uri'      => 'info-ui.php',
    ],
    [
        'priority' => 99,
        'key'      => 'extending',
        'name'     => __('Extending', 'corbidevrepositories'),
        'uri'      => 'info-extending.php',
    ],
];

$tabs = apply_filters('corbidev/info_tabs', $tabs);

/**
 * Sort tabs
 */
usort($tabs, function ($a, $b) {

    $a_external = !empty($a['path']);
    $b_external = !empty($b['path']);

    // interne avant externe
    if ($a_external !== $b_external) {
        return $a_external ? 1 : -1;
    }

    // priority
    $cmp = ($a['priority'] ?? 10) <=> ($b['priority'] ?? 10);
    if ($cmp !== 0) return $cmp;

    // tie-break
    return strcmp($a['key'], $b['key']);
});

/**
 * Current tab
 */
$current_key = isset($_GET['tab'])
    ? sanitize_key($_GET['tab'])
    : ($tabs[0]['key'] ?? null);

/**
 * Resolve current tab
 */
$current_tab = null;

foreach ($tabs as $tab) {
    if ($tab['key'] === $current_key) {
        $current_tab = $tab;
        break;
    }
}

if (!$current_tab && !empty($tabs)) {
    $current_tab = $tabs[0];
}

/**
 * Resolve template
 */
$template = null;

if (!empty($current_tab['path'])) {
    $external_path = realpath($current_tab['path']);
    if ($external_path && file_exists($external_path)) {
        $template = $external_path;
    }
}

if (!$template && !empty($current_tab['uri'])) {
    $internal_path = __DIR__ . '/templates/' . basename($current_tab['uri']);
    if (file_exists($internal_path)) {
        $template = $internal_path;
    }
}

?>

<?php
require __DIR__ . '/templates/info-shell-shadcn.php';
return;
?>

<div class="wrap corbidev-admin-info">

    <h1><?php echo esc_html__('Corbidev UI – Developer Guide', 'corbidevrepositories'); ?></h1>

    <!-- NAV -->
    <h2 class="nav-tab-wrapper">

        <?php foreach ($tabs as $tab): ?>

        <a href="<?php echo esc_url(admin_url('admin.php?page=corbidev-info&tab=' . $tab['key'])); ?>"
            class="nav-tab <?php echo ($current_tab && $current_tab['key'] === $tab['key']) ? 'nav-tab-active' : ''; ?>"
            data-tab="<?php echo esc_attr($tab['key']); ?>">
            <?php echo esc_html($tab['name']); ?>
        </a>

        <?php endforeach; ?>

    </h2>

    <!-- CONTENT -->
    <div class="corbidev-tab-content">

        <?php if ($template): ?>
        <?php require $template; ?>
        <?php else: ?>
        <p><?php echo esc_html__('Template not found.', 'corbidevrepositories'); ?></p>
        <?php endif; ?>

    </div>

</div>