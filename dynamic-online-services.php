<?php
/**
 * Plugin Name:       Dynamic Online Services
 * Plugin URI:        http://aabtaab.com/
 * Description:       A custom plugin for managing dynamic online services and their categories with a combined, sortable selection field and customizable styles.
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
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

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Current plugin version.
 *
 * @since 1.1.0
 */
define('DOC_VERSION', '1.1.0');

/**
 * Plugin directory path.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin basename.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Minimum PHP version required.
 *
 * @since 1.1.0
 */
define('DOC_MIN_PHP_VERSION', '7.2');

/**
 * Minimum WordPress version required.
 *
 * @since 1.1.0
 */
define('DOC_MIN_WP_VERSION', '5.0');

/**
 * CSS Breakpoint: Tablet (max-width).
 *
 * @since 1.1.0
 */
define('DOC_BREAKPOINT_TABLET', '768px');

/**
 * CSS Breakpoint: Mobile (max-width).
 *
 * @since 1.1.0
 */
define('DOC_BREAKPOINT_MOBILE', '480px');

/**
 * Maximum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DOC_MAX_GRID_COLUMNS', 6);

/**
 * Minimum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DOC_MIN_GRID_COLUMNS', 1);

/**
 * Maximum taxonomy hierarchy depth for permalink generation.
 * Prevents infinite loops in case of circular references in taxonomy hierarchy.
 *
 * @since 1.1.0
 */
define('DOC_MAX_TAXONOMY_DEPTH', 10);

/**
 * Default excerpt length for course descriptions.
 *
 * @since 1.1.0
 */
define('DOC_DEFAULT_EXCERPT_LENGTH', 20);

/**
 * Maximum posts per page for shortcode queries.
 * Prevents excessive database queries that could impact performance.
 *
 * @since 1.1.0
 */
define('DOC_MAX_POSTS_PER_PAGE', 1000);

/**
<?php
/**
 * Plugin Name:       Dynamic Online Services
 * Plugin URI:        http://aabtaab.com/
 * Description:       A custom plugin for managing dynamic online services and their categories with a combined, sortable selection field and customizable styles.
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
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

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Current plugin version.
 *
 * @since 1.1.0
 */
define('DOC_VERSION', '1.1.0');

/**
 * Plugin directory path.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin basename.
 *
 * @since 1.1.0
 */
define('DOC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Minimum PHP version required.
 *
 * @since 1.1.0
 */
define('DOC_MIN_PHP_VERSION', '7.2');

/**
 * Minimum WordPress version required.
 *
 * @since 1.1.0
 */
define('DOC_MIN_WP_VERSION', '5.0');

/**
 * CSS Breakpoint: Tablet (max-width).
 *
 * @since 1.1.0
 */
define('DOC_BREAKPOINT_TABLET', '768px');

/**
 * CSS Breakpoint: Mobile (max-width).
 *
 * @since 1.1.0
 */
define('DOC_BREAKPOINT_MOBILE', '480px');

/**
 * Maximum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DOC_MAX_GRID_COLUMNS', 6);

/**
 * Minimum grid columns for course cards.
 *
 * @since 1.1.0
 */
define('DOC_MIN_GRID_COLUMNS', 1);

/**
 * Maximum taxonomy hierarchy depth for permalink generation.
 * Prevents infinite loops in case of circular references in taxonomy hierarchy.
 *
 * @since 1.1.0
 */
define('DOC_MAX_TAXONOMY_DEPTH', 10);

/**
 * Default excerpt length for course descriptions.
 *
 * @since 1.1.0
 */
define('DOC_DEFAULT_EXCERPT_LENGTH', 20);

/**
 * Maximum posts per page for shortcode queries.
 * Prevents excessive database queries that could impact performance.
 *
 * @since 1.1.0
 */
define('DOC_MAX_POSTS_PER_PAGE', 1000);

/**
 * Default grid minimum width for course cards.
 *
 * @since 1.1.0
 */
define('DOC_DEFAULT_GRID_MIN_WIDTH', '280px');

// Load Autoloader
require_once DOC_PLUGIN_DIR . 'includes/Autoloader.php';
\DynamicOnlineServices\Autoloader::run();

// Initialize Plugin
function doc_init_plugin()
{
    \DynamicOnlineServices\Core\Plugin::get_instance();
}
add_action('plugins_loaded', 'doc_init_plugin');

// Load core files (Legacy support until fully refactored)
// require_once DOC_PLUGIN_DIR . 'includes/core/requirements.php'; // Moved to Plugin or Autoloaded?
// require_once DOC_PLUGIN_DIR . 'includes/core/activator.php';
// require_once DOC_PLUGIN_DIR . 'includes/core/deactivator.php';
// require_once DOC_PLUGIN_DIR . 'includes/core/uninstaller.php';
// require_once DOC_PLUGIN_DIR . 'includes/core/i18n.php';
// require_once DOC_PLUGIN_DIR . 'includes/core/loader.php'; // Deprecated

// Register activation, deactivation, and uninstall hooks
// We need to make sure these files are loaded or classes are available
require_once DOC_PLUGIN_DIR . 'includes/core/activator.php';
require_once DOC_PLUGIN_DIR . 'includes/core/deactivator.php';
require_once DOC_PLUGIN_DIR . 'includes/core/uninstaller.php';

register_activation_hook(__FILE__, 'doc_activate_plugin');
register_deactivation_hook(__FILE__, 'doc_deactivate_plugin');
register_uninstall_hook(__FILE__, 'doc_uninstall_plugin');

// Check requirements before loading plugin
require_once DOC_PLUGIN_DIR . 'includes/core/requirements.php';
if (!doc_check_requirements()) {
    return;
}