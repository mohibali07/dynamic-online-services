<?php
/**
 * Cards Shortcode Query Helper
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Build WP_Query arguments for cards shortcode.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return array WP_Query arguments.
 */
function dynos_get_cards_query_args($atts): array
{
	$settings = function_exists('dynos_sanitize_cpt_settings') ? dynos_sanitize_cpt_settings() : array();
	$service_slug = isset($settings['service_slug']) ? $settings['service_slug'] : 'services';

	$args = array(
		'post_type' => $service_slug,
		'post_status' => 'publish',
		'posts_per_page' => intval($atts['limit']),
		'orderby' => sanitize_sql_orderby($atts['orderby']),
		'order' => sanitize_text_field($atts['order']),
		'no_found_rows' => true, // Performance: skip pagination counting if not needed
		'update_post_meta_cache' => false, // Performance: skip meta cache if not using meta
		'update_post_term_cache' => true, // Performance: cache term relationships
	);

	// Filter by IDs
	if (!empty($atts['ids'])) {
		$ids = array_map('intval', explode(',', $atts['ids']));
		$args['post__in'] = $ids;
	}

	// Filter by Category
	if (!empty($atts['category'])) {
		$category = $atts['category'];
		$taxonomy = sanitize_text_field($atts['taxonomy']);

		// Check if category is IDs or Slugs
		$categories = explode(',', $category);
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
	if ('true' === $atts['show_pagination'] && $atts['paged'] > 1) {
		$args['paged'] = intval($atts['paged']);
		$args['no_found_rows'] = false; // Need total count for pagination
	}

	return apply_filters('dynos_service_cards_query_args', $args, $atts);
}

/**
 * Get cards query with caching.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return WP_Query Query object.
 */
function dynos_get_cards_query_cached(array $atts): \WP_Query
{
	// Load cache helper if available
	if (!function_exists('dynos_cache_get_or_set')) {
		$cache_file = dirname(__DIR__) . '/helpers/cache.php';
		if (file_exists($cache_file)) {
			require_once $cache_file;
		}
	}

	$args = dynos_get_cards_query_args($atts);

	// Use caching if available and pagination is disabled
	if (function_exists('dynos_cache_get_or_set') && 'true' !== $atts['show_pagination']) {
		$cache_key = 'dynos_cards_query_' . md5(wp_json_encode($args));

		$post_ids = dynos_cache_get_or_set(
			$cache_key,
			function () use ($args) {
				$query = new \WP_Query($args);
				return wp_list_pluck($query->posts, 'ID');
			},
			HOUR_IN_SECONDS
		);

		if (!empty($post_ids)) {
			$args['post__in'] = $post_ids;
			$args['orderby'] = 'post__in';
		}
	}

	return new \WP_Query($args);
}
