
<?php
declare(strict_types=1);

add_action('admin_menu', function () {
    if (is_multisite() && !is_network_admin()) {
        return;
    }

    add_menu_page(
        __('Repositories', 'corbidevrepositories'),
        __('Repositories', 'corbidevrepositories'),
        'manage_options',
        'corbidev-repositories',
        function () {
            include CDR_PATH . 'admin/pages/repositories-page.php';
        },
        'dashicons-database',
        58
    );
});
