<?php
/**
 * Autoloader
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices;

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
		if ( strpos( $class_name, 'TechmireSolutions\\DynamicOnlineServices\\' ) !== 0 ) {
			return;
		}

		// Remove namespace from class name.
		$relative_class = substr( $class_name, strlen( 'TechmireSolutions\\DynamicOnlineServices\\' ) );

		// Base directory for our namespace
		$base_dir = DYNOS_PLUGIN_DIR . 'includes/';

		// Split into parts to handle the last part (classname) differently
		$parts = explode( '\\', $relative_class );
		$class_file = array_pop( $parts );

		// Convert class name to kebab-case
		$class_file = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $class_file ) );

		// Convert directory parts to lowercase
		$directory = '';
		if ( ! empty( $parts ) ) {
			$directory = strtolower( implode( '/', $parts ) ) . '/';
		}

		// Try class- prefix
		$file_path_class = $base_dir . $directory . 'class-' . $class_file . '.php';

		// Try interface- prefix
		$file_path_interface = $base_dir . $directory . 'interface-' . $class_file . '.php';

		// Try trait- prefix
		$file_path_trait = $base_dir . $directory . 'trait-' . $class_file . '.php';

		if ( file_exists( $file_path_class ) ) {
			require_once $file_path_class;
		} elseif ( file_exists( $file_path_interface ) ) {
			require_once $file_path_interface;
		} elseif ( file_exists( $file_path_trait ) ) {
			require_once $file_path_trait;
		} else {
			// Log missing class file in debug mode to help identify issues
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log(
					sprintf(
						'DYNOS Autoloader: Class file not found for %s. Tried: %s, %s, %s',
						$class_name,
						$file_path_class,
						$file_path_interface,
						$file_path_trait
					)
				);
			}
		}
	}
}
