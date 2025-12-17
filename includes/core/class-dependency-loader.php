<?php
/**
 * Dependency Loader Class
 *
 * Responsible for loading all plugin file dependencies.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * DependencyLoader class.
 *
 * Single responsibility: Load all required plugin files.
 *
 * @since 1.1.3
 */
class DependencyLoader
{

	/**
	 * Load all plugin dependencies.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function load(): void
	{
		self::load_helpers();
		self::load_core();
		self::load_post_types();
		self::load_faqs();
	}

	/**
	 * Load helper functions and classes.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function load_helpers(): void
	{
		// Helpers are autoloaded or loaded where needed
	}

	/**
	 * Load core functionality files.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function load_core(): void
	{
		// Internationalization is now handled by ComponentRegistry

	}

	/**
	 * Load post type related files.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function load_post_types(): void
	{
		// Required for CPT settings sanitization and validation used in constructors
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/class-sanitization.php';

		// Required for permalink structure (filter hook)
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/permalink.php';

		if ( \is_admin() ) {
			require_once DYNOS_PLUGIN_DIR . 'includes/taxonomies/class-taxonomy-fields.php';
		}
	}

	/**
	 * Load FAQ related files.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function load_faqs(): void
	{
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/class-meta-box.php';
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/class-saver.php';
	}
}
