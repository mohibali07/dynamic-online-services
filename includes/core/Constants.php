<?php
/**
 * Plugin Constants Management
 *
 * Provides centralized access to plugin constants with filter hooks
 * for maximum flexibility and customization.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 * @since 1.2.0
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Constants Class
 *
 * Manages all plugin constants with filter hooks and static caching.
 * This allows developers to override default values while maintaining
 * excellent performance through request-level caching.
 *
 * @since 1.2.0
 */
class Constants
{

	/**
	 * Get tablet breakpoint value.
	 *
	 * @since 1.2.0
	 * @return string CSS max-width value for tablet breakpoint.
	 */
	public static function get_breakpoint_tablet(): string
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the tablet breakpoint value.
			 *
			 * @since 1.2.0
			 * @param string $breakpoint Default tablet breakpoint (768px).
			 */
			$value = apply_filters('dynos_breakpoint_tablet', '768px');
		}

		return $value;
	}

	/**
	 * Get mobile breakpoint value.
	 *
	 * @since 1.2.0
	 * @return string CSS max-width value for mobile breakpoint.
	 */
	public static function get_breakpoint_mobile(): string
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the mobile breakpoint value.
			 *
			 * @since 1.2.0
			 * @param string $breakpoint Default mobile breakpoint (480px).
			 */
			$value = apply_filters('dynos_breakpoint_mobile', '480px');
		}

		return $value;
	}

	/**
	 * Get maximum grid columns.
	 *
	 * @since 1.2.0
	 * @return int Maximum number of columns in grid layout.
	 */
	public static function get_max_grid_columns(): int
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the maximum grid columns.
			 *
			 * @since 1.2.0
			 * @param int $columns Default maximum columns (6).
			 */
			$value = (int) apply_filters('dynos_max_grid_columns', 6);
		}

		return $value;
	}

	/**
	 * Get minimum grid columns.
	 *
	 * @since 1.2.0
	 * @return int Minimum number of columns in grid layout.
	 */
	public static function get_min_grid_columns(): int
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the minimum grid columns.
			 *
			 * @since 1.2.0
			 * @param int $columns Default minimum columns (1).
			 */
			$value = (int) apply_filters('dynos_min_grid_columns', 1);
		}

		return $value;
	}

	/**
	 * Get maximum taxonomy hierarchy depth.
	 *
	 * Prevents infinite loops in case of circular references in taxonomy hierarchy.
	 *
	 * @since 1.2.0
	 * @return int Maximum depth to traverse.
	 */
	public static function get_max_taxonomy_depth(): int
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the maximum taxonomy hierarchy depth.
			 *
			 * @since 1.2.0
			 * @param int $depth Default maximum depth (10).
			 */
			$value = (int) apply_filters('dynos_max_taxonomy_depth', 10);
		}

		return $value;
	}

	/**
	 * Get default excerpt length.
	 *
	 * @since 1.2.0
	 * @return int Number of words in excerpt.
	 */
	public static function get_default_excerpt_length(): int
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the default excerpt length.
			 *
			 * @since 1.2.0
			 * @param int $length Default excerpt length in words (20).
			 */
			$value = (int) apply_filters('dynos_default_excerpt_length', 20);
		}

		return $value;
	}

	/**
	 * Get maximum posts per page.
	 *
	 * Prevents excessive database queries that could impact performance.
	 *
	 * @since 1.2.0
	 * @return int Maximum posts to query.
	 */
	public static function get_max_posts_per_page(): int
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the maximum posts per page.
			 *
			 * @since 1.2.0
			 * @param int $max Default maximum posts (1000).
			 */
			$value = (int) apply_filters('dynos_max_posts_per_page', 1000);
		}

		return $value;
	}

	/**
	 * Get default grid minimum width.
	 *
	 * @since 1.2.0
	 * @return string CSS min-width value for grid items.
	 */
	public static function get_default_grid_min_width(): string
	{
		static $value = null;

		if (null === $value) {
			/**
			 * Filter the default grid minimum width.
			 *
			 * @since 1.2.0
			 * @param string $width Default minimum width (280px).
			 */
			$value = apply_filters('dynos_default_grid_min_width', '280px');
		}

		return $value;
	}

	/**
	 * Reset all cached values.
	 *
	 * Useful for testing or when filter values change mid-request.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function reset_cache(): void
	{
		// This method intentionally does nothing in production.
		// Static variables are automatically reset on new requests.
		// This placeholder exists for potential future testing needs.
		do_action('dynos_constants_cache_reset');
	}
}
