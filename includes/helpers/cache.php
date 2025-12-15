<?php
/**
 * Caching Helper Functions
 *
 * Provides centralized caching functionality using WordPress transients.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Get cached data or execute callback and cache result.
 *
 * @since 1.1.0
 * @param string   $key      Cache key.
 * @param callable $callback Callback to execute if cache miss.
 * @param int      $expiration Cache expiration in seconds. Default 1 hour.
 * @return mixed Cached data or callback result.
 */
function dynos_cache_get_or_set(string $key, callable $callback, int $expiration = HOUR_IN_SECONDS): mixed
{
	$cached = get_transient($key);

	if (false !== $cached) {
		return $cached;
	}

	$data = $callback();
	set_transient($key, $data, $expiration);

	return $data;
}

/**
 * Clear cache by key or pattern.
 *
 * @since 1.1.0
 * @param string $key_or_pattern Cache key or pattern (use * for wildcard).
 * @return int Number of cache entries cleared.
 */
function dynos_cache_clear(string $key_or_pattern): int
{
	global $wpdb;

	$count = 0;

	// If exact key, delete it
	if (strpos($key_or_pattern, '*') === false) {
		if (delete_transient($key_or_pattern)) {
			$count = 1;
		}
		return $count;
	}

	// Pattern matching - get all transients matching pattern
	$pattern = str_replace('*', '%', $key_or_pattern);
	$transient_prefix = '_transient_';

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$transients = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like($transient_prefix) . $pattern
		)
	);

	foreach ($transients as $transient) {
		$key = str_replace($transient_prefix, '', $transient);
		if (delete_transient($key)) {
			++$count;
		}
	}

	return $count;
}

/**
 * Clear all plugin caches.
 *
 * @since 1.1.0
 * @return int Number of cache entries cleared.
 */
function dynos_cache_clear_all(): int
{
	return dynos_cache_clear('dynos_*');
}

/**
 * Get cached terms for a taxonomy.
 *
 * @since 1.1.0
 * @param string $taxonomy Taxonomy slug.
 * @param array  $args     Optional. get_terms() arguments.
 * @return array|WP_Error Array of term objects or WP_Error on failure.
 */
function dynos_cache_get_terms(string $taxonomy, array $args = array()): array|\WP_Error
{
	$cache_key = 'dynos_terms_' . md5($taxonomy . wp_json_encode($args));

	return dynos_cache_get_or_set(
		$cache_key,
		function () use ($taxonomy, $args) {
			$args['taxonomy'] = $taxonomy;
			return get_terms($args);
		},
		HOUR_IN_SECONDS
	);
}

/**
 * Get cached posts query.
 *
 * @since 1.1.0
 * @param array $args WP_Query arguments.
 * @return WP_Query Query object.
 */
function dynos_cache_get_posts(array $args): \WP_Query
{
	$cache_key = 'dynos_posts_' . md5(wp_json_encode($args));

	$post_ids = dynos_cache_get_or_set(
		$cache_key,
		function () use ($args) {
			$query = new \WP_Query($args);
			return wp_list_pluck($query->posts, 'ID');
		},
		HOUR_IN_SECONDS
	);

	// Return fresh query with cached IDs
	if (!empty($post_ids)) {
		$args['post__in'] = $post_ids;
		$args['orderby'] = 'post__in';
	}

	return new \WP_Query($args);
}

/**
 * Clear cache when post is saved.
 *
 * @since 1.1.0
 * @param int $post_id Post ID.
 * @return void
 */
function dynos_cache_clear_on_post_save(int $post_id): void
{
	// Clear post caches
	dynos_cache_clear('dynos_posts_*');

	// Clear term caches if post has taxonomies
	$post_type = get_post_type($post_id);
	$taxonomies = get_object_taxonomies($post_type);

	if (!empty($taxonomies)) {
		dynos_cache_clear('dynos_terms_*');
	}
}
add_action('save_post', 'dynos_cache_clear_on_post_save');

/**
 * Clear cache when term is saved.
 *
 * @since 1.1.0
 * @param int $term_id Term ID.
 * @return void
 */
function dynos_cache_clear_on_term_save(int $term_id): void
{
	dynos_cache_clear('dynos_terms_*');
	dynos_cache_clear('dynos_posts_*'); // Posts might be filtered by this term
}
add_action('created_term', 'dynos_cache_clear_on_term_save');
add_action('edited_term', 'dynos_cache_clear_on_term_save');
add_action('delete_term', 'dynos_cache_clear_on_term_save');

/**
 * Clear cache when settings are updated.
 *
 * @since 1.1.0
 * @return void
 */
function dynos_cache_clear_on_settings_update(): void
{
	dynos_cache_clear_all();
}
add_action('update_option_dynos_options', 'dynos_cache_clear_on_settings_update');
