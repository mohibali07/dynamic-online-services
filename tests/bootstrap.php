<?php
/**
 * PHPUnit Bootstrap
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

// Add error reporting to suppression of deprecation notices
// This is critical for WP_Mock compatibility with PHPUnit 9/10
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/wordpress/' );
}
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
}
if ( ! defined( 'WPMU_PLUGIN_DIR' ) ) {
	define( 'WPMU_PLUGIN_DIR', ABSPATH . 'wp-content/mu-plugins' );
}
if ( ! defined( 'DYNOS_PLUGIN_DIR' ) ) {
	define( 'DYNOS_PLUGIN_DIR', dirname( dirname( __FILE__ ) ) . '/' );
}
if ( ! defined( 'DYNOS_MIN_GRID_COLUMNS' ) ) {
    define( 'DYNOS_MIN_GRID_COLUMNS', 1 );
}
if ( ! defined( 'DYNOS_MAX_GRID_COLUMNS' ) ) {
    define( 'DYNOS_MAX_GRID_COLUMNS', 4 );
}
if ( ! defined( 'DYNOS_DEFAULT_GRID_MIN_WIDTH' ) ) {
    define( 'DYNOS_DEFAULT_GRID_MIN_WIDTH', '300px' );
}
if ( ! defined( 'DYNOS_PLUGIN_URL' ) ) {
	define( 'DYNOS_PLUGIN_URL', 'http://example.com/wp-content/plugins/dynamic-online-services/' );
}
if ( ! defined( 'DYNOS_VERSION' ) ) {
	define( 'DYNOS_VERSION', '1.0.0' );
}
if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 24 * 60 * 60 );
}

// Define operational constants
if ( ! defined( 'DYNOS_MAX_POSTS_PER_PAGE' ) ) {
    define( 'DYNOS_MAX_POSTS_PER_PAGE', 100 );
}

// Verify composer autoloader
if ( ! file_exists( DYNOS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	throw new Exception( 'Vendor directory not found. Please run "composer install".' );
}

// Require composer autoloader
require_once DYNOS_PLUGIN_DIR . 'vendor/autoload.php';

// Initialize WP_Mock
WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();

// Load namespaced stubs for local function resolution in tests
require_once __DIR__ . '/namespaced-stubs.php';

// Define mock class_exists if not exists
if (!function_exists('class_exists')) {
    function class_exists($class) {
        return \WP_Mock::userFunction('class_exists', ['args' => [$class], 'return' => true]);
    }
}

/**
 * Base test class for the plugin.
 */
abstract class DYNOS_TestCase extends \WP_Mock\Tools\TestCase {
    public function setUp(): void
    {
        parent::setUp();
        // \WP_Mock::setUp(); // Redundant, called by parent
        require_once __DIR__ . '/stubs.php';

        // Define default mocks for core functions to avoid undefined function errors
        // Force these expectations to ensure return values are "false" (not null) if not superseded

    }

    public function tearDown(): void {
        \WP_Mock::tearDown();
    }
}
