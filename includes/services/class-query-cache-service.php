<?php
/**
 * Query Cache Service Class
 *
 * Responsible for caching WordPress query results.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

use TechmireSolutions\DynamicOnlineServices\Helpers\Cache;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * QueryCacheService class.
 *
 * Single responsibility: Cache WordPress queries (posts and terms).
 *
 * @since 1.1.3
 */
class QueryCacheService
{

	/**
	 * Get cached terms for a taxonomy.
	 *
	 * @since 1.1.3
	 * @param string $taxonomy Taxonomy slug.
	 * @param array  $args     Optional. get_terms() arguments.
	 * @return array|\WP_Error Array of term objects or WP_Error on failure.
	 */
	public static function get_terms(string $taxonomy, array $args = [])
	{
		return Cache::get_or_set(
			'dynos_terms_' . $taxonomy . '_' . md5(maybe_serialize($args)),
			function () use ($taxonomy, $args) {
				return get_terms(
					array_merge(
						['taxonomy' => $taxonomy, 'hide_empty' => false],
						$args
					)
				);
			},
			HOUR_IN_SECONDS
		);
	}

	/**
	 * Get cached posts query.
	 *
	 * @since 1.1.3
	 * @param array $args WP_Query arguments.
	 * @return \WP_Query Query object.
	 */
	public static function get_posts(array $args): \WP_Query
	{
		$cache_key = 'dynos_posts_' . md5(maybe_serialize($args));
		$cached_ids = get_transient($cache_key);

		if (false !== $cached_ids && is_array($cached_ids)) {
			// Return cached query with post__in
			return new \WP_Query(
				array_merge(
					$args,
					[
						'post__in' => $cached_ids,
						'orderby' => 'post__in',
						'no_found_rows' => true,
						'update_post_meta_cache' => true,
						'update_post_term_cache' => true,
					]
				)
			);
		}

		// No cache, execute query
		$query = new \WP_Query($args);

		// Cache post IDs only (more efficient than full objects)
		if ($query->have_posts()) {
			$post_ids = wp_list_pluck($query->posts, 'ID');
			set_transient($cache_key, $post_ids, HOUR_IN_SECONDS);
		}

		return $query;
	}

	/**
	 * Clear all query caches.
	 *
	 * @since 1.1.3
	 * @return int Number of caches cleared.
	 */
	public static function clear_all(): int
	{
		$cleared = 0;
		$cleared += Cache::clear('dynos_terms_*');
		$cleared += Cache::clear('dynos_posts_*');
		return $cleared;
	}
}
