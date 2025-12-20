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
$dynos_tablet_breakpoint = get_transient('dynos_breakpoint_tablet_cached');
if (false === $dynos_tablet_breakpoint) {
	// Run filter and cache result
	$dynos_tablet_breakpoint = apply_filters('dynos_breakpoint_tablet', '768px');
	set_transient('dynos_breakpoint_tablet_cached', $dynos_tablet_breakpoint, DAY_IN_SECONDS);
}
if (!defined('DYNOS_BREAKPOINT_TABLET')) {
	define('DYNOS_BREAKPOINT_TABLET', $dynos_tablet_breakpoint);
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
$dynos_mobile_breakpoint = get_transient('dynos_breakpoint_mobile_cached');
if (false === $dynos_mobile_breakpoint) {
	// Run filter and cache result
	$dynos_mobile_breakpoint = apply_filters('dynos_breakpoint_mobile', '480px');
	set_transient('dynos_breakpoint_mobile_cached', $dynos_mobile_breakpoint, DAY_IN_SECONDS);
}
if (!defined('DYNOS_BREAKPOINT_MOBILE')) {
	define('DYNOS_BREAKPOINT_MOBILE', $dynos_mobile_breakpoint);
}

/**
 * Maximum grid columns for course cards.
 *
 * Filterable via 'dynos_max_grid_columns' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$dynos_max_columns = (int) apply_filters('dynos_max_grid_columns', 6);
if (!defined('DYNOS_MAX_GRID_COLUMNS')) {
	define('DYNOS_MAX_GRID_COLUMNS', $dynos_max_columns);
}

/**
 * Minimum grid columns for course cards.
 *
 * Filterable via 'dynos_min_grid_columns' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$dynos_min_columns = (int) apply_filters('dynos_min_grid_columns', 1);
if (!defined('DYNOS_MIN_GRID_COLUMNS')) {
	define('DYNOS_MIN_GRID_COLUMNS', $dynos_min_columns);
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
$dynos_max_depth = (int) apply_filters('dynos_max_taxonomy_depth', 10);
if (!defined('DYNOS_MAX_TAXONOMY_DEPTH')) {
	define('DYNOS_MAX_TAXONOMY_DEPTH', $dynos_max_depth);
}

/**
 * Default excerpt length for course descriptions.
 *
 * Filterable via 'dynos_default_excerpt_length' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$dynos_excerpt_length = (int) apply_filters('dynos_default_excerpt_length', 20);
if (!defined('DYNOS_DEFAULT_EXCERPT_LENGTH')) {
	define('DYNOS_DEFAULT_EXCERPT_LENGTH', $dynos_excerpt_length);
}

/**
 * Maximum posts per page for shortcode queries.
 * Prevents excessive database queries that could impact performance.
 *
 * Default: 1000 posts
 *
 * WHY 1000?
 * - Allows displaying large service catalogs in a single view
 * - Balanced between flexibility and performance
 * - Most sites have fewer than 1000 services
 * - Database queries remain performant up to this limit with proper indexing
 *
 * WHEN TO REDUCE:
 * - If your server has limited memory (< 256MB PHP memory limit)
 * - If you notice slow page loads with large result sets
 * - If you're on shared hosting with strict resource limits
 *
 * WHEN TO INCREASE:
 * - If you have 1000+ services and need to display them all
 * - If you have dedicated hosting with ample resources
 * - Only if you've tested performance with your actual dataset
 *
 * RECOMMENDED VALUES:
 * - Small sites (< 100 services): 100-200
 * - Medium sites (100-500 services): 300-500
 * - Large sites (500-1000 services): 500-1000
 * - Enterprise (1000+ services): 1000-2000 (test performance!)
 *
 * Filterable via 'dynos_max_posts_per_page' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$dynos_max_posts = (int) apply_filters('dynos_max_posts_per_page', 1000);
if (!defined('DYNOS_MAX_POSTS_PER_PAGE')) {
	define('DYNOS_MAX_POSTS_PER_PAGE', $dynos_max_posts);
}


/**
 * Default grid minimum width for course cards.
 *
 * Filterable via 'dynos_default_grid_min_width' hook.
 *
 * @since 1.1.0
 */
// Always run filter for transparency
$dynos_grid_min_width = apply_filters('dynos_default_grid_min_width', '280px');
if (!defined('DYNOS_DEFAULT_GRID_MIN_WIDTH')) {
	define('DYNOS_DEFAULT_GRID_MIN_WIDTH', $dynos_grid_min_width);
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
$dynos_max_faqs = (int) apply_filters('dynos_max_faqs_per_post', 100);
if (!defined('DYNOS_MAX_FAQS_PER_POST')) {
	define('DYNOS_MAX_FAQS_PER_POST', $dynos_max_faqs);
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
