<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Define plugin directory path just in case.
 */
if ( ! defined( 'DYNOS_PLUGIN_DIR' ) ) {
	define( 'DYNOS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Include the uninstaller class.
require_once DYNOS_PLUGIN_DIR . 'includes/core/Uninstaller.php';

// Run the uninstall.
\TechmireSolutions\DynamicOnlineServices\Core\Uninstaller::uninstall();
