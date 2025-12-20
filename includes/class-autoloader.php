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

		// Split into parts to handle mapping and filenames
		$parts = explode( '\\', $relative_class );
		$root_namespace = array_shift( $parts ); // e.g. Admin, Cpt, Shortcodes

		// Define base directory based on root namespace
		switch ( $root_namespace ) {
			case 'Admin':
				$base_dir = DYNOS_PLUGIN_DIR . 'admin/';
				break;
			case 'Cpt':
				$base_dir = DYNOS_PLUGIN_DIR . 'cpt/';
				break;
			case 'Shortcodes':
				$base_dir = DYNOS_PLUGIN_DIR . 'frontend/shortcodes/';
				break;
			case 'Renderers':
				$base_dir = DYNOS_PLUGIN_DIR . 'frontend/renderers/';
				break;
			case 'Services':
				$base_dir = DYNOS_PLUGIN_DIR . 'frontend/services/';
				break;
			case 'FAQs':
				$base_dir = DYNOS_PLUGIN_DIR . 'includes/faqs/';
				break;
			default:
				// Fallback for Core, Helpers, Taxonomies, etc.
				// If the namespace was Core, it continues from there.
				// We popped 'Core' so we need to put it back into directory path logic effectively?
				// Actually, if it's 'Core', relative_class was 'Core\Activator'.
				// $parts is now ['Activator'].
				// The previous logic used the whole relative string.
				// Let's adjust.
				$base_dir = DYNOS_PLUGIN_DIR . 'includes/';
				// If it wasn't one of the special moved directories, we assume it's in includes.
				// But we popped the first part. We need to handle that.
				// For 'Core', it IS inside 'includes/core'.
				// So if we have 'Core', we want directory to start with 'core/'.
				// The logic below constructs directory from $parts.
				// So if we put $root_namespace back into $parts? No, 'Admin' -> 'admin/' is the root.
				// 'Core' -> 'includes/core/'.
				// So for default, we want to treat $root_namespace as the first directory part.
				array_unshift($parts, $root_namespace);
				break;
		}

		$class_file = array_pop( $parts );

		// Convert class name to kebab-case
		$class_file = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $class_file ) );

		// Convert directory parts to kebab-case
		$directory = '';
		if ( ! empty( $parts ) ) {
			$kebab_parts = array_map(
				function( $part ) {
					return strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $part ) );
				},
				$parts
			);
			$directory = implode( '/', $kebab_parts ) . '/';
		}

		// For mapped directories, the mapping already points to the root of that component.
		// e.g. Admin\Settings -> admin/class-settings.php (if no subnamespace)
		// $parts for Admin\Settings (after shift) is []. $directory is ''.
		// $base_dir is .../admin/. Final: .../admin/class-settings.php. Correct.

		// e.g. Core\Activator -> Default -> unshift -> ['Core'].
		// $parts is now ['Core']. $directory is 'core/'.
		// $base_dir is .../includes/. Final: .../includes/core/class-activator.php. Correct.

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
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
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
