<?php
/**
 * Hero Cache Class
 *
 * Handles transient caching for hero section data to improve performance.
 *
 * @package Dynamic_Online_Services
 * @subpackage Cache
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HeroCache class.
 */
class HeroCache {

	/**
	 * Cache expiration time (24 hours).
	 *
	 * @var int
	 */
	const CACHE_EXPIRATION = 86400; // 24 hours in seconds.

	/**
	 * Cache prefix.
	 *
	 * @var string
	 */
	const CACHE_PREFIX = 'dynos_hero_';

	/**
	 * Generate cache key for hero data.
	 *
	 * @param string $type Type of hero (category or service).
	 * @param int    $id   Term ID or Post ID.
	 * @return string Cache key.
	 */
	public static function generate_key( string $type, int $id ): string {
		return self::CACHE_PREFIX . $type . '_' . $id;
	}

	/**
	 * Get cached hero data.
	 *
	 * @param string $key Cache key.
	 * @return mixed|false Cached data or false if not found.
	 */
	public static function get( string $key ) {
		$cached = get_transient( $key );

		// Debug logging in WP_DEBUG mode.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			if ( false !== $cached ) {
				do_action( 'dynos_hero_cache_hit', $key );
			} else {
				do_action( 'dynos_hero_cache_miss', $key );
			}
		}

		return $cached;
	}

	/**
	 * Set cached hero data.
	 *
	 * @param string $key  Cache key.
	 * @param mixed  $data Data to cache.
	 * @param int    $expiration Cache expiration time in seconds (default: 24 hours).
	 * @return bool True on success, false on failure.
	 */
	public static function set( string $key, $data, int $expiration = self::CACHE_EXPIRATION ): bool {
		$result = set_transient( $key, $data, $expiration );

		// Allow filtering cache expiration.
		$expiration = apply_filters( 'dynos_hero_cache_expiration', $expiration, $key );

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			do_action( 'dynos_hero_cache_set', $key, $expiration );
		}

		return $result;
	}

	/**
	 * Delete cached hero data.
	 *
	 * @param string $key Cache key.
	 * @return bool True on success, false on failure.
	 */
	public static function delete( string $key ): bool {
		return delete_transient( $key );
	}

	/**
	 * Invalidate all hero caches.
	 *
	 * @return int Number of caches invalidated.
	 */
	public static function invalidate_all(): int {
		global $wpdb;

		$count = 0;

		// Delete all transients with hero cache prefix.
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$transients = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT option_name FROM {$wpdb->options}
				WHERE option_name LIKE %s",
				'_transient_' . self::CACHE_PREFIX . '%'
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		foreach ( $transients as $transient ) {
			$key = str_replace( '_transient_', '', $transient );
			if ( delete_transient( $key ) ) {
				++$count;
			}
		}

		do_action( 'dynos_hero_cache_invalidated', $count );

		return $count;
	}

	/**
	 * Invalidate cache for specific post or term.
	 *
	 * @param string $type Type of hero (category or service).
	 * @param int    $id   Term ID or Post ID.
	 * @return bool True on success, false on failure.
	 */
	public static function invalidate( string $type, int $id ): bool {
		$key = self::generate_key( $type, $id );
		return self::delete( $key );
	}

	/**
	 * Initialize cache hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		// Invalidate cache when hero settings are updated.
		add_action( 'update_option_dynos_options', array( __CLASS__, 'invalidate_all' ) );

		// Invalidate cache when a service post is updated.
		add_action( 'save_post', array( __CLASS__, 'invalidate_on_save_post' ), 10, 2 );

		// Invalidate cache when a term is updated.
		add_action( 'edited_term', array( __CLASS__, 'invalidate_on_edited_term' ), 10, 3 );

		// LiteSpeed Cache integration.
		add_action( 'dynos_hero_cache_invalidated', array( __CLASS__, 'purge_litespeed_cache' ) );
	}

	/**
	 * Invalidate cache when a post is saved.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @return void
	 */
	public static function invalidate_on_save_post( int $post_id, $post ): void {
		$settings     = \TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization::sanitize_cpt_settings();
		$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

		if ( $post->post_type === $service_slug ) {
			self::invalidate( 'service', $post_id );
		}
	}

	/**
	 * Invalidate cache when a term is edited.
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 * @return void
	 */
	public static function invalidate_on_edited_term( int $term_id, int $tt_id, string $taxonomy ): void {
		$settings      = \TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

		if ( $taxonomy === $taxonomy_slug ) {
			self::invalidate( 'category', $term_id );
		}
	}

	/**
	 * Purge LiteSpeed Cache when hero cache is invalidated.
	 *
	 * @param int $count Number of caches invalidated.
	 * @return void
	 */
	public static function purge_litespeed_cache( int $count ): void {
		// Check if LiteSpeed Cache is active.
		if ( ! defined( 'LSCWP_V' ) ) {
			return;
		}

		// Purge all caches.
		if ( method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
			\LiteSpeed_Cache_API::purge_all();
		}
	}
}
