<?php
/**
 * Slug Initializer Class
 *
 * Responsible for initializing and tracking post type/taxonomy slugs.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\Settings\Defaults;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * SlugInitializer class.
 *
 * Single responsibility: Initialize and track slugs for permalinks.
 *
 * @since 1.1.3
 */
class SlugInitializer
{

	/**
	 * Initialize slug options on plugin activation.
	 *
	 * Sets up previous slug tracking for permalink management.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function initialize(): void
	{
		$options = \get_option('dynos_options', []);
		$defaults = Defaults::get_options();

		// Ensure defaults are set if missing using get_option default fallback for single options
		// But here we are dealing with the main options array
		$updated = false;

		if (empty($options['service_post_type_slug'])) {
			$options['service_post_type_slug'] = $defaults['service_post_type_slug'];
			$updated = true;
		}

		if (empty($options['service_taxonomy_slug'])) {
			$options['service_taxonomy_slug'] = $defaults['service_taxonomy_slug'];
			$updated = true;
		}

		if ($updated) {
			if ( \is_admin() ) {
				\update_option('dynos_options', $options);
			}
		}

		// Set previous slugs for tracking, using the potentially updated options
		self::set_previous_slugs(
			$options['service_post_type_slug'],
			$options['service_taxonomy_slug']
		);
	}

	/**
	 * Set previous slug options for tracking.
	 *
	 * @since 1.1.3
	 * @param string $service_slug   Service post type slug.
	 * @param string $taxonomy_slug  Taxonomy slug.
	 * @return void
	 */
	private static function set_previous_slugs(string $service_slug, string $taxonomy_slug): void
	{
		\update_option('dynos_previous_service_slug', $service_slug);
		\update_option('dynos_previous_taxonomy_slug', $taxonomy_slug);
	}
}
