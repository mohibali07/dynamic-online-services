<?php
/**
 * Autoloader
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

namespace DynamicOnlineServices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloader class.
 */
class Autoloader {


	/**
	 * Run autoloader.
	 *
	 * @return void
	 */
	public static function run(): void {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload.
	 *
	 * @param string $class_name Class name.
	 * @return void
	 */
	public static function autoload( $class_name ): void {
		// Check if class is in our namespace
		if ( strpos( $class_name, 'DynamicOnlineServices\\' ) !== 0 ) {
			return;
		}

		// Remove namespace from class name.
		$relative_class = substr( $class_name, strlen( 'DynamicOnlineServices\\' ) );

		// Base directory for our namespace
		$base_dir = DYNOS_PLUGIN_DIR . 'includes/';

		// Replace namespace separators with directory separators
		// DynamicOnlineServices\Settings\PageRenderer -> includes/Settings/PageRenderer.php
		$file_path = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		// Check for file existence cases (PascalCase vs lowercase/kebab-case)
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
			return;
		}

		// Fallback: Check for lowercase directory/filename (common in WP)
		// DynamicOnlineServices\Settings\PageRenderer -> includes/settings/pagerenderer.php or similar if needed
		// For now, let's try strict mapping or mapping to purely lowercase if not found.
		$lower_path = $base_dir . strtolower( str_replace( '\\', '/', $relative_class ) ) . '.php';
		if ( file_exists( $lower_path ) ) {
			require_once $lower_path;
			return;
		}
	}
}
