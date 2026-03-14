<?php
/**
 * Plugin Name:       Corbidev Repositories
 * Plugin URI:        https://github.com/CorbiDev/wp-corbidev-repositories
 * Depot Github:      wp-corbidev-repositories
 * Description:       Gestion des dépôts GitHub Corbidev et installation des plugins et thèmes.
 * Version:           1.0.0
 * Author:            CorbiDev
 * Author URI:        https://github.com/CorbiDev
 *
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       corbidevrepositories
 * Domain Path:       /languages
 *
 * Requires at least: 6.0
 * Tested up to:      6.5
 * Requires PHP:      8.4
 *
 * Icone:             assets/icons/favicon.png
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

$plugin_data = get_file_data(
    __FILE__,
    array( 'Version' => 'Version' )
);
define('CDR_VERSION', $plugin_data['Version']);
define('CDR_PLUGIN_FILE', __FILE__);
define('CDR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CDR_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once CDR_PLUGIN_DIR . 'loader/bootstrap.php';
