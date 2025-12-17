<?php
/**
 * Rate Limiter Helper
 *
 * Prevents abuse by limiting action frequency per user.
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
 * Rate Limiter Class
 *
 * Provides rate limiting functionality to prevent abuse of save operations
 * and AJAX endpoints. Uses WordPress transients for temporary storage.
 *
 * @since 1.2.0
 */
class RateLimiter {

	/**
	 * Check if action is allowed based on rate limit.
	 *
	 * @since 1.2.0
	 * @param string $action Action identifier (e.g., 'faq_save', 'settings_update').
	 * @param int    $user_id User ID (0 for guest users).
	 * @param int    $max_attempts Maximum attempts allowed within time window.
	 * @param int    $time_window Time window in seconds.
	 * @return bool True if action is allowed, false if rate limited.
	 */
	public static function check(string $action, int $user_id, int $max_attempts = 10, int $time_window = 60): bool {
		// Allow bypassing rate limiting via filter
		if (apply_filters('dynos_disable_rate_limiting', false)) {
			return true;
		}

		// Allow per-action override
		if (apply_filters("dynos_disable_rate_limiting_{$action}", false)) {
			return true;
		}

		$transient_key = self::get_transient_key($action, $user_id);
		$attempts = (int) get_transient($transient_key);

		if ($attempts >= $max_attempts) {
			// Fire action for logging/monitoring
			do_action('dynos_rate_limit_exceeded', $action, $user_id, $attempts, $max_attempts);

			// Log in debug mode
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log(
					sprintf(
						'DYNOS Rate Limit: User %d exceeded %d attempts for action "%s"',
						$user_id,
						$max_attempts,
						$action
					)
				);
			}

			return false;
		}

		// Increment attempt counter
		set_transient($transient_key, $attempts + 1, $time_window);

		return true;
	}

	/**
	 * Reset rate limit for specific action and user.
	 *
	 * @since 1.2.0
	 * @param string $action Action identifier.
	 * @param int    $user_id User ID.
	 * @return bool True on success.
	 */
	public static function reset(string $action, int $user_id): bool {
		$transient_key = self::get_transient_key($action, $user_id);
		return delete_transient($transient_key);
	}

	/**
	 * Get remaining attempts for user and action.
	 *
	 * @since 1.2.0
	 * @param string $action Action identifier.
	 * @param int    $user_id User ID.
	 * @param int    $max_attempts Maximum attempts allowed.
	 * @return int Remaining attempts (0 if rate limited).
	 */
	public static function get_remaining(string $action, int $user_id, int $max_attempts = 10): int {
		$transient_key = self::get_transient_key($action, $user_id);
		$attempts = (int) get_transient($transient_key);

		$remaining = $max_attempts - $attempts;
		return max(0, $remaining);
	}

	/**
	 * Get transient key for rate limiting.
	 *
	 * @since 1.2.0
	 * @param string $action Action identifier.
	 * @param int    $user_id User ID.
	 * @return string Transient key.
	 */
	private static function get_transient_key(string $action, int $user_id): string {
		// Sanitize action to prevent issues
		$action = sanitize_key($action);
		return "dynos_rate_limit_{$action}_{$user_id}";
	}

	/**
	 * Clear all rate limiting data.
	 *
	 * WARNING: This clears all rate limits for all users.
	 * Use only for debugging or after significant changes.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public static function clear_all(): void {
		global $wpdb;

		// Delete all rate limit transients
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like('_transient_dynos_rate_limit_') . '%'
			)
		);

		// Fire action for logging
		do_action('dynos_rate_limits_cleared');
	}
}
