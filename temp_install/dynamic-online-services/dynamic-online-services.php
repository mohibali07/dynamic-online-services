<?php
/**
 * Plugin Name:       Dynamic Online Services
 * Plugin URI:        http://aabtaab.com/
 * Description:       A custom plugin for managing dynamic online services and their categories with a combined, sortable selection field and customizable styles.
 * Version:           1.1.3
 * Requires at least: 6.9
 * Requires PHP:      8.3
 * Author:            Techmire Solutions
 * Author URI:        http://techmiresolutions.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       dynamic-online-services
 * Domain Path:       /languages
 * Network:           false
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Current plugin version.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_VERSION')) {
	define('DYNOS_VERSION', '1.1.3');
}

/**
 * Plugin directory path.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_PLUGIN_DIR')) {
	define('DYNOS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

/**
 * Plugin directory URL.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_PLUGIN_URL')) {
	define('DYNOS_PLUGIN_URL', plugin_dir_url(__FILE__));
}

/**
 * Plugin basename.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_PLUGIN_BASENAME')) {
	define('DYNOS_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

/**
 * Minimum PHP version required.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_MIN_PHP_VERSION')) {
	define('DYNOS_MIN_PHP_VERSION', '8.3');
}

/**
 * Minimum WordPress version required.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_MIN_WP_VERSION')) {
	define('DYNOS_MIN_WP_VERSION', '6.9');
}


// Load Autoloader.
require_once DYNOS_PLUGIN_DIR . 'includes/class-autoloader.php';
\TechmireSolutions\DynamicOnlineServices\Autoloader::run();

// Check requirements before loading plugin.
if (!\TechmireSolutions\DynamicOnlineServices\Core\Requirements::check()) {
	return;
}

// Register activation, deactivation, and uninstall hooks.
require_once DYNOS_PLUGIN_DIR . 'includes/core/class-activator.php';
require_once DYNOS_PLUGIN_DIR . 'includes/core/class-uninstaller.php';

register_activation_hook(__FILE__, array('\TechmireSolutions\DynamicOnlineServices\Core\Activator', 'activate'));
register_deactivation_hook(__FILE__, array('\TechmireSolutions\DynamicOnlineServices\Core\Deactivator', 'deactivate'));
register_uninstall_hook(__FILE__, array('\TechmireSolutions\DynamicOnlineServices\Core\Uninstaller', 'uninstall'));

/**
 * Initialize Plugin.
 *
 * @since 1.1.0
 * @return void
 */
function dynos_init_plugin(): void
{
	\TechmireSolutions\DynamicOnlineServices\Core\Plugin::get_instance();
	\TechmireSolutions\DynamicOnlineServices\Helpers\Options::init();
}
add_action('plugins_loaded', 'dynos_init_plugin');
