<?php
/**
 * Cards Query Service
 *
 * Handles query argument construction and execution for service cards.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

use TechmireSolutions\DynamicOnlineServices\Helpers\Cache;
use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cards Query Service class.
 */
class CardsQueryService
{
	/**
	 * Get cards query with optional caching.
	 *
	 * @since 1.2.0
	 * @param array $atts Shortcode attributes.
	 * @return \WP_Query Query object.
	 */
	public static function get_query(array $atts): \WP_Query
	{
		$args = self::get_query_args($atts);

		// Use caching if available and pagination is disabled
		// We avoid caching paginated results to prevent cache pollution unless explicitly desired
		if ('true' !== $atts['show_pagination'] && class_exists(Cache::class)) {
			// Cache::get_posts handles the caching logic internally using the args as key
			return Cache::get_posts($args);
		}

		return new \WP_Query($args);
	}

	/**
	 * Build WP_Query arguments for cards shortcode.
	 *
	 * @since 1.2.0
	 * @param array $atts Shortcode attributes.
	 * @return array WP_Query arguments.
	 */
	public static function get_query_args(array $atts): array
	{
		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug = isset($settings['service_slug']) ? $settings['service_slug'] : 'services';

		// Default limit to -1 if not set or invalid
		$limit = isset($atts['limit']) ? intval($atts['limit']) : -1;

		// FIX: Enforce maximum posts per page to prevent memory exhaustion
		// -1 means "unlimited" which is dangerous on sites with 1000+ posts
		// Protect against both unlimited (-1) and excessive values
		$max_posts = \TechmireSolutions\DynamicOnlineServices\Core\Configuration::get_max_posts_per_page();
		if (-1 === $limit || $limit > $max_posts) {
			$limit = $max_posts;
		}

		// Allow 0 to disable query (edge case for custom filtering)
		if (0 > $limit && -1 !== $limit) {
			$limit = $max_posts;
		}

		$args = array(
			'post_type' => $service_slug,
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'orderby' => isset($atts['orderby']) ? dynos_validate_orderby($atts['orderby'], 'date') : 'date',
			'order' => isset($atts['order']) ? sanitize_text_field($atts['order']) : 'DESC',
			'no_found_rows' => true, // Performance: skip pagination counting if not needed
			'update_post_meta_cache' => true, // We need meta cache (preloaded in shortcode)
			'update_post_term_cache' => true, // Performance: cache term relationships
		);

		// Filter by IDs
		if (!empty($atts['ids'])) {
			$ids = array_map('intval', explode(',', $atts['ids']));

			// Remove zeros and validate - filter out invalid IDs
			$ids = array_filter($ids, function ($id) {
				return $id > 0;
			});

			if (!empty($ids)) {
				$args['post__in'] = $ids;
			} else {
				// Log invalid IDs in debug mode
				if (defined('WP_DEBUG') && WP_DEBUG) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log('DYNOS: Invalid post IDs provided to cards shortcode: ' . $atts['ids']);
				}
			}
		}

		// Filter by Category
		if (!empty($atts['category'])) {
			$category = $atts['category'];
			$taxonomy = isset($atts['taxonomy']) ? sanitize_text_field($atts['taxonomy']) : 'service-category';

			// Validate taxonomy exists
			if (!taxonomy_exists($taxonomy)) {
				if (defined('WP_DEBUG') && WP_DEBUG) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log(sprintf('DYNOS: Invalid taxonomy "%s" in cards shortcode', $taxonomy));
				}
				// Return empty query instead of error
				return array(
					'post_type' => $service_slug,
					'post__in' => array(0), // Will return no results
					'posts_per_page' => 1,
				);
			}

			// Check if category is IDs or Slugs
			$categories = explode(',', $category);

			// Determine field based on first item
			$field = is_numeric($categories[0]) ? 'term_id' : 'slug';

			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => $field,
					'terms' => $categories,
				),
			);
		}

		// Pagination
		if (isset($atts['show_pagination']) && 'true' === $atts['show_pagination']) {
			// Check paged attribute, fallback to query var
			$paged = isset($atts['paged']) ? intval($atts['paged']) : 1;
			// If paged not passed in atts, try get_query_var (standard WP pagination)
			if ($paged <= 1 && get_query_var('paged')) {
				$paged = get_query_var('paged');
			}

			if ($paged > 1) {
				$args['paged'] = intval($paged);
			}

			$args['no_found_rows'] = false; // Need total count for pagination
		}

		return apply_filters('dynos_service_cards_query_args', $args, $atts);
	}
}
