<?php
/**
 * Configuration Class
 *
 * Handles plugin configuration and constants with filterable values.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configuration class.
 */
class Configuration {

	/**
	 * Get tablet breakpoint.
	 *
	 * @return string
	 */
	public static function get_tablet_breakpoint(): string {
		$breakpoint = get_transient( 'dynos_breakpoint_tablet_cached' );
		if ( false === $breakpoint ) {
			$breakpoint = (string) apply_filters( 'dynos_breakpoint_tablet', '768px' );
			set_transient( 'dynos_breakpoint_tablet_cached', $breakpoint, DAY_IN_SECONDS );
		}
		return $breakpoint;
	}

	/**
	 * Get mobile breakpoint.
	 *
	 * @return string
	 */
	public static function get_mobile_breakpoint(): string {
		$breakpoint = get_transient( 'dynos_breakpoint_mobile_cached' );
		if ( false === $breakpoint ) {
			$breakpoint = (string) apply_filters( 'dynos_breakpoint_mobile', '480px' );
			set_transient( 'dynos_breakpoint_mobile_cached', $breakpoint, DAY_IN_SECONDS );
		}
		return $breakpoint;
	}

	/**
	 * Get max grid columns.
	 *
	 * @return int
	 */
	public static function get_max_grid_columns(): int {
		return (int) apply_filters( 'dynos_max_grid_columns', 6 );
	}

	/**
	 * Get min grid columns.
	 *
	 * @return int
	 */
	public static function get_min_grid_columns(): int {
		return (int) apply_filters( 'dynos_min_grid_columns', 1 );
	}

	/**
	 * Get max taxonomy depth.
	 *
	 * @return int
	 */
	public static function get_max_taxonomy_depth(): int {
		return (int) apply_filters( 'dynos_max_taxonomy_depth', 10 );
	}

	/**
	 * Get default excerpt length.
	 *
	 * @return int
	 */
	public static function get_default_excerpt_length(): int {
		return (int) apply_filters( 'dynos_default_excerpt_length', 20 );
	}

	/**
	 * Get max posts per page.
	 *
	 * @return int
	 */
	public static function get_max_posts_per_page(): int {
		return (int) apply_filters( 'dynos_max_posts_per_page', 1000 );
	}

	/**
	 * Get default grid min width.
	 *
	 * @return string
	 */
	public static function get_grid_min_width(): string {
		return (string) apply_filters( 'dynos_default_grid_min_width', '280px' );
	}

	/**
	 * Get max FAQs per post.
	 *
	 * @return int
	 */
	public static function get_max_faqs_per_post(): int {
		return (int) apply_filters( 'dynos_max_faqs_per_post', 100 );
	}
}
