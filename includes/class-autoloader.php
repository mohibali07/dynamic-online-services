<?php
/**
 * Autoloader
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Autoloader class.
 */
class Autoloader
{


	/**
	 * Run autoloader.
	 *
	 * @return void
	 */
	public static function run(): void
	{
		spl_autoload_register([__CLASS__, 'autoload']);
	}

	/**
	 * Autoload.
	 *
	 * @param string $class_name Class name.
	 * @return void
	 */
	public static function autoload(string $class_name): void
	{
		// Check if class is in our namespace
		if (strpos($class_name, 'TechmireSolutions\\DynamicOnlineServices\\') !== 0) {
			return;
		}

		// Remove namespace from class name.
		$relative_class = substr($class_name, strlen('TechmireSolutions\\DynamicOnlineServices\\'));

		// Base directory for our namespace
		$base_dir = DYNOS_PLUGIN_DIR . 'includes/';

		// Split into parts to handle the last part (classname) differently
		$parts = explode('\\', $relative_class);
		$class_file = array_pop($parts);

		// FIX: Add error handling for regex operations
		try {
			// Convert class name to kebab-case with validation
			$class_file_kebab = preg_replace('/(?<!^)[A-Z]/', '-$0', $class_file);

			// Validate preg_replace didn't error
			if ($class_file_kebab === null) {
				// preg_replace returns null on error
				if (defined('WP_DEBUG') && WP_DEBUG) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log(
						sprintf(
							'DYNOS Autoloader: preg_replace failed for class name "%s" (error code: %d)',
							$class_file,
							preg_last_error()
						)
					);
				}
				return;
			}

			$class_file = strtolower($class_file_kebab);

			// Convert directory parts to kebab-case
			$directory = '';
			if (!empty($parts)) {
				$parts = array_map(
					function ($part) {
						$part_kebab = preg_replace('/(?<!^)[A-Z]/', '-$0', $part);

						// Validate regex result
						if ($part_kebab === null) {
							// Log error and return original  (fallback)
							if (defined('WP_DEBUG') && WP_DEBUG) {
								// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
								error_log('DYNOS Autoloader: preg_replace failed for directory part: ' . $part);
							}
							return strtolower($part);
						}

						return strtolower($part_kebab);
					},
					$parts
				);
				$directory = implode('/', $parts) . '/';
			}
		} catch (\Exception $e) {
			// Log exception and return to prevent fatal errors
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log('DYNOS Autoloader Exception: ' . $e->getMessage());
			}
			return;
		}

		// Try class- prefix
		$file_path_class = $base_dir . $directory . 'class-' . $class_file . '.php';

		// Try interface- prefix
		$file_path_interface = $base_dir . $directory . 'interface-' . $class_file . '.php';

		// Try trait- prefix
		$file_path_trait = $base_dir . $directory . 'trait-' . $class_file . '.php';

		if (file_exists($file_path_class)) {
			require_once $file_path_class;
		} elseif (file_exists($file_path_interface)) {
			require_once $file_path_interface;
		} elseif (file_exists($file_path_trait)) {
			require_once $file_path_trait;
		} else {
			// FIX: Log when file not found (helps debugging)
			if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log(
					sprintf(
						'DYNOS Autoloader: Could not find class "%s". Tried paths: %s, %s, %s',
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
