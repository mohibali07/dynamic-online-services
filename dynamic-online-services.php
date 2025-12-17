<?php
/**
 * Plugin Name:       Dynamic Online Services
 * Plugin URI:        http://aabtaab.com/
 * Description:       A custom plugin for managing dynamic online services and their categories with a combined, sortable selection field and customizable styles.
 * Version:           1.1.2
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
	define('DYNOS_VERSION', '1.1.2');
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

/**
 * CSS Breakpoint: Tablet (max-width).
 *
 * PERFORMANCE NOTE: Filter results are cached in transients for 24 hours
 * to avoid running filters on every page load. Clear with:
 * delete_transient('dynos_breakpoint_tablet_cached')
 *
 * Filterable via 'dynos_breakpoint_tablet' hook.
 *
 * @since 1.1.0
 */
// Check cache first for performance
$tablet_breakpoint = get_transient('dynos_breakpoint_tablet_cached');
if (false === $tablet_breakpoint) {
	// Run filter and cache result
	$tablet_breakpoint = apply_filters('dynos_breakpoint_tablet', '768px');
	set_transient('dynos_breakpoint_tablet_cached', $tablet_breakpoint, DAY_IN_SECONDS);
}
if (!defined('DYNOS_BREAKPOINT_TABLET')) {
	define('DYNOS_BREAKPOINT_TABLET', $tablet_breakpoint);
}

/**
 * CSS Breakpoint: Mobile (max-width).
 *
 * PERFORMANCE NOTE: Filter results are cached in transients for 24 hours
 * to avoid running filters on every page load. Clear with:
 * delete_transient('dynos_breakpoint_mobile_cached')
 *
 * Filterable via 'dynos_breakpoint_mobile' hook.
 *
 * @since 1.1.0
 */
// Check cache first for performance
$mobile_breakpoint = get_transient('dynos_breakpoint_mobile_cached');
if (false === $mobile_breakpoint) {
	// Run filter and cache result
	$mobile_breakpoint = apply_filters('dynos_breakpoint_mobile', '480px');
	set_transient('dynos_breakpoint_mobile_cached', $mobile_breakpoint, DAY_IN_SECONDS);
}
if (!defined('DYNOS_BREAKPOINT_MOBILE')) {
	define('DYNOS_BREAKPOINT_MOBILE', $mobile_breakpoint);
}

/**
 * Maximum grid columns for course cards.
 *
 * Filterable via 'dynos_max_grid_columns' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$max_columns = (int) apply_filters('dynos_max_grid_columns', 6);
if (!defined('DYNOS_MAX_GRID_COLUMNS')) {
	define('DYNOS_MAX_GRID_COLUMNS', $max_columns);
}

/**
 * Minimum grid columns for course cards.
 *
 * Filterable via 'dynos_min_grid_columns' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$min_columns = (int) apply_filters('dynos_min_grid_columns', 1);
if (!defined('DYNOS_MIN_GRID_COLUMNS')) {
	define('DYNOS_MIN_GRID_COLUMNS', $min_columns);
}

/**
 * Maximum taxonomy hierarchy depth for permalink generation.
 * Prevents infinite loops in case of circular references in taxonomy hierarchy.
 *
 * Filterable via 'dynos_max_taxonomy_depth' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$max_depth = (int) apply_filters('dynos_max_taxonomy_depth', 10);
if (!defined('DYNOS_MAX_TAXONOMY_DEPTH')) {
	define('DYNOS_MAX_TAXONOMY_DEPTH', $max_depth);
}

/**
 * Default excerpt length for course descriptions.
 *
 * Filterable via 'dynos_default_excerpt_length' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$excerpt_length = (int) apply_filters('dynos_default_excerpt_length', 20);
if (!defined('DYNOS_DEFAULT_EXCERPT_LENGTH')) {
	define('DYNOS_DEFAULT_EXCERPT_LENGTH', $excerpt_length);
}

/**
 * Maximum posts per page for shortcode queries.
 * Prevents excessive database queries that could impact performance.
 *
 * Default: 100 posts (CHANGED from 1000 in v1.2.0 for better performance)
 *
 * WHY 100?
 * - Provides good balance between display flexibility and performance
 * - Prevents slow page loads on large databases
 * - Encourages use of pagination for better user experience
 * - Database queries remain fast even with complex filtering
 *
 * WHEN TO INCREASE:
 * - Only if you absolutely must display more than 100 items at once
 * - Test performance with your actual dataset before deploying
 * - Consider pagination as a better alternative for user experience
 *
 * RECOMMENDED VALUES:
 * - Small sites (< 100 services): 50-100
 * - Medium sites (100-500 services): 100-200
 * - Large sites (500+ services): Use pagination instead of increasing this limit
 *
 * Filterable via 'dynos_max_posts_per_page' hook.
 *
 * @since 1.1.0
 * @since 1.2.0 Changed default from 1000 to 100 for better performance.
 */
// Always run filter for transparency
$max_posts = (int) apply_filters('dynos_max_posts_per_page', 100);
if (!defined('DYNOS_MAX_POSTS_PER_PAGE')) {
	define('DYNOS_MAX_POSTS_PER_PAGE', $max_posts);
}


/**
 * Default grid minimum width for course cards.
 *
 * Filterable via 'dynos_default_grid_min_width' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$grid_min_width = apply_filters('dynos_default_grid_min_width', '280px');
if (!defined('DYNOS_DEFAULT_GRID_MIN_WIDTH')) {
	define('DYNOS_DEFAULT_GRID_MIN_WIDTH', $grid_min_width);
}

/**
 * Maximum FAQs allowed per post.
 * Prevents memory and performance issues with unbounded FAQ arrays.
 *
 * Filterable via 'dynos_max_faqs_per_post' hook.
 *
 * @since 1.1.1
 */
// Always run filter for transparency
$max_faqs = (int) apply_filters('dynos_max_faqs_per_post', 100);
if (!defined('DYNOS_MAX_FAQS_PER_POST')) {
	define('DYNOS_MAX_FAQS_PER_POST', $max_faqs);
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

	// Initialize Settings
	\TechmireSolutions\DynamicOnlineServices\Settings\Registration::init();
	\TechmireSolutions\DynamicOnlineServices\Settings\Menu::register();
    \TechmireSolutions\DynamicOnlineServices\Taxonomies\TaxonomyFields::init();
}
add_action('plugins_loaded', 'dynos_init_plugin');
