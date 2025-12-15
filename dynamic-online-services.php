<?php
/**
 * Plugin Name:       Dynamic Online Services
 * Plugin URI:        http://aabtaab.com/
 * Description:       A custom plugin for managing dynamic online services and their categories with a combined, sortable selection field and customizable styles.
 * Version:           1.1.1
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
define('DYNOS_VERSION', '1.1.1');

/**
 * Plugin directory path.
 *
 * @since 1.1.0
 */
define('DYNOS_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL.
 *
 * @since 1.1.0
 */
define('DYNOS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin basename.
 *
 * @since 1.1.0
 */
define('DYNOS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Minimum PHP version required.
 *
 * @since 1.1.0
 */
define('DYNOS_MIN_PHP_VERSION', '8.3');

/**
 * Minimum WordPress version required.
 *
 * @since 1.1.0
 */
define('DYNOS_MIN_WP_VERSION', '6.9');

/**
 * CSS Breakpoint: Tablet (max-width).
 *
 * @since 1.1.0
 */
define('DYNOS_BREAKPOINT_TABLET', '768px');

/**
 * CSS Breakpoint: Mobile (max-width).
 *
 * @since 1.1.0
 */
define('DYNOS_BREAKPOINT_MOBILE', '480px');

/**
 * Maximum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DYNOS_MAX_GRID_COLUMNS', 6);

/**
 * Minimum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DYNOS_MIN_GRID_COLUMNS', 1);

/**
 * Maximum taxonomy hierarchy depth for permalink generation.
 * Prevents infinite loops in case of circular references in taxonomy hierarchy.
 *
 * @since 1.1.0
 */
define('DYNOS_MAX_TAXONOMY_DEPTH', 10);

/**
 * Default excerpt length for course descriptions.
 *
 * @since 1.1.0
 */
define('DYNOS_DEFAULT_EXCERPT_LENGTH', 20);

/**
 * Maximum posts per page for shortcode queries.
 * Prevents excessive database queries that could impact performance.
 *
 * @since 1.1.0
 */
define('DYNOS_MAX_POSTS_PER_PAGE', 1000);

/**
 * Default grid minimum width for course cards.
 *
 * @since 1.1.0
 */
define('DYNOS_DEFAULT_GRID_MIN_WIDTH', '280px');

// Load Autoloader.
require_once DYNOS_PLUGIN_DIR . 'includes/Autoloader.php';
\DynamicOnlineServices\Autoloader::run();

// Check requirements before loading plugin.
require_once DYNOS_PLUGIN_DIR . 'includes/Core/requirements.php';
if (!dynos_check_requirements()) {
	return;
}

// Register activation, deactivation, and uninstall hooks.
require_once DYNOS_PLUGIN_DIR . 'includes/Core/Activator.php';
require_once DYNOS_PLUGIN_DIR . 'includes/Core/Deactivator.php';
require_once DYNOS_PLUGIN_DIR . 'includes/Core/Uninstaller.php';

register_activation_hook(__FILE__, array('\DynamicOnlineServices\Core\Activator', 'activate'));
register_deactivation_hook(__FILE__, 'dynos_deactivate_plugin');
register_uninstall_hook(__FILE__, array('\DynamicOnlineServices\Core\Uninstaller', 'uninstall'));

/**
 * Initialize Plugin.
 *
 * @since 1.1.0
 * @return void
 */
function dynos_init_plugin(): void
{
	\DynamicOnlineServices\Core\Plugin::get_instance();
	\DynamicOnlineServices\Helpers\Options::init();
}
add_action('plugins_loaded', 'dynos_init_plugin');
