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
				// If it wasn't one of the special moved directories, we assume it's in includes.
				$base_dir = DYNOS_PLUGIN_DIR . 'includes/';
				// Put the root namespace back as a directory part
				array_unshift($parts, $root_namespace);
				break;
		}

		$original_class_file = array_pop( $parts );

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

		// Convert class name to kebab-case for WP standard
		$class_file_kebab = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $original_class_file ) );

		// Array of possible file paths to check
		$possible_files = array(
			// 1. WP Standard: class-kebab-case.php
			$base_dir . $directory . 'class-' . $class_file_kebab . '.php',
			// 2. PSR-4 Standard: PascalCase.php (Used by ServicePostType.php)
			$base_dir . $directory . $original_class_file . '.php',
			// 3. WP Interface: interface-kebab-case.php
			$base_dir . $directory . 'interface-' . $class_file_kebab . '.php',
			// 4. WP Trait: trait-kebab-case.php
			$base_dir . $directory . 'trait-' . $class_file_kebab . '.php',
		);

		foreach ( $possible_files as $file ) {
			if ( file_exists( $file ) ) {
				require_once $file;
				return;
			}
		}
	}
}
