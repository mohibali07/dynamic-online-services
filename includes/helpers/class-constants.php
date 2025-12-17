<?php
/**
 * Constants Helper
 *
 * Manages filterable constants with proper timing and caching.
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
 * Constants Helper Class
 *
 * Handles filterable constants that need to run after plugins_loaded
 * to ensure other plugins can modify them via filters.
 *
 * @since 1.2.0
 */
class Constants {

	/**
	 * Initialize constants after plugins are loaded.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function init(): void {
		add_action('plugins_loaded', [__CLASS__, 'set_breakpoints'], 5);
	}

	/**
	 * Set responsive breakpoints with filtering.
	 *
	 * Runs on plugins_loaded to allow other plugins to filter values.
	 * Results are cached for performance.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function set_breakpoints(): void {
		// Get cached or filter values
		$tablet = self::get_cached_filter('dynos_breakpoint_tablet', '768px');
		$mobile = self::get_cached_filter('dynos_breakpoint_mobile', '480px');

		// Store in global for runtime access
		// We can't redefine constants, so use a global variable
		global $dynos_responsive_breakpoints;
		$dynos_responsive_breakpoints = [
			'tablet' => $tablet,
			'mobile' => $mobile,
		];

		// Fire action to allow other code to use these values
		do_action('dynos_breakpoints_loaded', $tablet, $mobile);
	}

	/**
	 * Get filtered value with caching.
	 *
	 * @since 1.2.0
	 * @param string $filter_name Filter hook name.
	 * @param mixed  $default Default value.
	 * @return mixed Filtered value.
	 */
	private static function get_cached_filter(string $filter_name, $default) {
		$cache_key = $filter_name . '_cached';
		$cached = get_transient($cache_key);

		if (false !== $cached) {
			return $cached;
		}

		$value = apply_filters($filter_name, $default);
		set_transient($cache_key, $value, DAY_IN_SECONDS);

		return $value;
	}

	/**
	 * Get breakpoint value at runtime.
	 *
	 * @since 1.2.0
	 * @param string $type Breakpoint type: 'tablet' or 'mobile'.
	 * @return string Breakpoint value.
	 */
	public static function get_breakpoint(string $type): string {
		global $dynos_responsive_breakpoints;

		// Fallback to constants if global not set yet
		if (!isset($dynos_responsive_breakpoints[$type])) {
			if ('tablet' === $type) {
				return defined('DYNOS_BREAKPOINT_TABLET') ? DYNOS_BREAKPOINT_TABLET : '768px';
			}
			if ('mobile' === $type) {
				return defined('DYNOS_BREAKPOINT_MOBILE') ? DYNOS_BREAKPOINT_MOBILE : '480px';
			}
		}

		return $dynos_responsive_breakpoints[$type] ?? '';
	}

	/**
	 * Clear cached constants.
	 *
	 * Call this on plugin activation or update to ensure
	 * fresh values are retrieved.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function clear_cache(): void {
		delete_transient('dynos_breakpoint_tablet_cached');
		delete_transient('dynos_breakpoint_mobile_cached');

		// Fire action for extensibility
		do_action('dynos_constants_cache_cleared');
	}
}

// Initialize on plugins_loaded
Constants::init();
