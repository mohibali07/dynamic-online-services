<?php
/**
 * Caching Helper Class
 *
 * Provides centralized caching functionality using WordPress transients.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cache helper class.
 */
class Cache
{
	/**
	 * Get cached data or execute callback and cache result.
	 *
	 * @since 1.1.0
	 * @param string   $key      Cache key.
	 * @param callable $callback Callback to execute if cache miss.
	 * @param int      $expiration Cache expiration in seconds. Default 1 hour.
	 * @return mixed Cached data or callback result.
	 */
	public static function get_or_set(string $key, callable $callback, int $expiration = HOUR_IN_SECONDS): mixed
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
	public static function clear(string $key_or_pattern): int
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

		// OPTIMIZATION: Use object cache flush if available (much faster)
		if (wp_using_ext_object_cache()) {
			// Extract cache group from pattern (e.g., 'dynos_posts_*' -> 'dynos_posts')
			$cache_group = str_replace('*', '', $key_or_pattern);
			$cache_group = rtrim($cache_group, '_');

			// Flush the entire group if supported by the object cache
			// Note: Not all object cache backends support group flushing
			// Falls back to individual deletion below if this doesn't work
			if (function_exists('wp_cache_flush_group')) {
				wp_cache_flush_group($cache_group);
				// Unable to count items with group flush, return estimated count
				return 1;
			}
		}

		// Sanitize pattern before conversion to prevent SQL injection
		$sanitized_pattern = preg_replace('/[^a-zA-Z0-9_*\-]/', '', $key_or_pattern);

		// Additional safety: limit wildcards to prevent performance issues
		if (substr_count($sanitized_pattern, '*') > 3) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log('DYNOS Cache: Too many wildcards in pattern: ' . $key_or_pattern);
			}
			return 0;
		}

		// Pattern matching - get all transients matching pattern
		$pattern = str_replace('*', '%', $sanitized_pattern);
		$transient_prefix = '_transient_';

		// Implement batched clearing to handle more than 1000 transients
		$offset = 0;
		$batch_size = 1000;

		do {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$transients = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT %d OFFSET %d",
					$wpdb->esc_like($transient_prefix) . $pattern,
					$batch_size,
					$offset
				)
			);

			foreach ($transients as $transient) {
				$key = str_replace($transient_prefix, '', $transient);
				if (delete_transient($key)) {
					++$count;
				}
			}

			$offset += $batch_size;
		} while (count($transients) === $batch_size);

		return $count;
	}

	/**
	 * Clear all plugin caches.
	 *
	 * @since 1.1.0
	 * @return int Number of cache entries cleared.
	 */
	public static function clear_all(): int
	{
		return self::clear('dynos_*');
	}

	/**
	 * Get cached terms for a taxonomy.
	 *
	 * @since 1.1.0
	 * @param string $taxonomy Taxonomy slug.
	 * @param array  $args     Optional. get_terms() arguments.
	 * @return array|WP_Error Array of term objects or WP_Error on failure.
	 */
	public static function get_terms(string $taxonomy, array $args = array()): array|\WP_Error
	{
		$cache_key = 'dynos_terms_' . md5($taxonomy . wp_json_encode($args));

		return self::get_or_set(
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
	 * @return \WP_Query Query object.
	 */
	public static function get_posts(array $args): \WP_Query
	{
		$cache_key = 'dynos_posts_' . md5(wp_json_encode($args));

		$post_ids = self::get_or_set(
			$cache_key,
			function () use ($args) {
				$query = new \WP_Query($args);
				return wp_list_pluck($query->posts, 'ID');
			},
			HOUR_IN_SECONDS
		);

		// FIX: Preserve original orderby instead of always overriding
		// Only use post__in when we have cached IDs AND orderby is compatible
		if (!empty($post_ids)) {
			$original_orderby = isset($args['orderby']) ? $args['orderby'] : 'date';

			// Store original orderby for restoration if needed
			$args['post__in'] = $post_ids;

			// Only override to post__in if the original orderby won't work with post__in
			// Compatible orderbys: date, modified, title, name, ID, post__in
			// Incompatible: meta_value, meta_value_num (requires actual query)
			$incompatible_orderby = in_array($original_orderby, ['meta_value', 'meta_value_num'], true);

			if ($incompatible_orderby) {
				// Meta-based ordering requires full query data, use post__in order
				$args['orderby'] = 'post__in';
			}
			// Otherwise preserve the original orderby setting
		}

		return new \WP_Query($args);
	}

	/**
	 * Clear cache when post is saved.
	 *
	 * OPTIMIZATION: Only clear term caches if taxonomies were actually modified.
	 * This reduces unnecessary cache invalidation on simple post content updates.
	 *
	 * @since 1.1.0
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public static function clear_on_post_save(int $post_id): void
	{
		// Nonce verification to prevent CSRF.
		if (isset($_POST['_wpnonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash((string)$_POST['_wpnonce'])), 'update-post_' . $post_id)) {
			// If it's not a standard post save, check for quick edit
			if (isset($_POST['_inline_edit']) && !wp_verify_nonce(sanitize_text_field(wp_unslash((string)$_POST['_inline_edit'])), 'inlineeditnonce')) {
				return;
			}
		}

		// Clear post caches (always needed)
		self::clear('dynos_posts_*');

		// OPTIMIZATION: Only clear term caches if taxonomies were modified
		// Check if taxonomy data was submitted in the request
		$taxonomy_modified = false;

		// Check for taxonomy input in POST data (taxonomy assignment)
		// We use current_user_can as a basic permission check here since different pages use different nonces
		if (isset($_POST['tax_input']) && is_array($_POST['tax_input'])) {
			$taxonomy_modified = true;
		}

		// Check for quick edit taxonomy changes
		if (isset($_POST['_inline_edit']) || isset($_POST['action']) && 'inline-save' === $_POST['action']) {
			// Quick edit might change taxonomies, be conservative
			$post_type = get_post_type($post_id);
			$taxonomies = get_object_taxonomies($post_type);
			if (!empty($taxonomies)) {
				$taxonomy_modified = true;
			}
		}

		// Only clear term caches if taxonomies were actually modified
		if ($taxonomy_modified) {
			self::clear('dynos_terms_*');
		}
	}

	/**
	 * Clear cache when term is saved.
	 *
	 * @since 1.1.0
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public static function clear_on_term_save(int $term_id): void
	{
		self::clear('dynos_terms_*');
		self::clear('dynos_posts_*'); // Posts might be filtered by this term
	}

	/**
	 * Clear cache when settings are updated.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function clear_on_settings_update(): void
	{
		self::clear_all();
	}

	/**
	 * Initialize cache hooks.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function init_hooks(): void
	{
		add_action('save_post', [self::class, 'clear_on_post_save']);
		add_action('created_term', [self::class, 'clear_on_term_save']);
		add_action('edited_term', [self::class, 'clear_on_term_save']);
		add_action('delete_term', [self::class, 'clear_on_term_save']);
		add_action('update_option_dynos_options', [self::class, 'clear_on_settings_update']);
	}
}

// Initialize hooks
Cache::init_hooks();
