<?php
/**
 * Admin Notice Helper Class
 *
 * Handles user-friendly admin notices and error messages.
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
 * Admin notices helper class.
 */
class AdminNotices
{
	/**
	 * Display a user-friendly admin notice.
	 *
	 * @since 1.1.0
	 * @param string $message Message to display.
	 * @param string $type Notice type: 'error', 'warning', 'success', 'info'. Default 'info'.
	 * @param bool   $dismissible Whether the notice is dismissible. Default true.
	 * @return void
	 */
	public static function show(string $message, string $type = 'info', bool $dismissible = true): void
	{
		if (empty($message)) {
			return;
		}

		// Validate notice type
		$allowed_types = ['error', 'warning', 'success', 'info'];
		if (!in_array($type, $allowed_types, true)) {
			$type = 'info';
		}

		$class = 'notice notice-' . esc_attr($type);
		if ($dismissible) {
			$class .= ' is-dismissible';
		}

		printf(
			'<div class="%1$s"><p>%2$s</p></div>',
			esc_attr($class),
			wp_kses_post($message)
		);
	}

	/**
	 * Display a success notice.
	 *
	 * @since 1.1.0
	 * @param string $message Success message.
	 * @return void
	 */
	public static function success(string $message): void
	{
		self::show($message, 'success', true);
	}

	/**
	 * Display an error notice.
	 *
	 * @since 1.1.0
	 * @param string $message Error message.
	 * @return void
	 */
	public static function error(string $message): void
	{
		self::show($message, 'error', true);
	}

	/**
	 * Display a warning notice.
	 *
	 * @since 1.1.0
	 * @param string $message Warning message.
	 * @return void
	 */
	public static function warning(string $message): void
	{
		self::show($message, 'warning', true);
	}

	/**
	 * Display an info notice.
	 *
	 * @since 1.1.0
	 * @param string $message Info message.
	 * @return void
	 */
	public static function info(string $message): void
	{
		self::show($message, 'info', true);
	}

	/**
	 * Add a user-friendly error message to settings errors.
	 *
	 * @since 1.1.0
	 * @param string $message Error message.
	 * @param string $code Error code. Default empty.
	 * @return void
	 */
	public static function add_settings_error(string $message, string $code = ''): void
	{
		if (empty($message)) {
			return;
		}

		$code = !empty($code) ? sanitize_key($code) : 'dynos_general_error';

		add_settings_error(
			'Dynamic_Online_Services',
			$code,
			esc_html($message),
			'error'
		);
	}

	/**
	 * Add a user-friendly success message to settings errors.
	 *
	 * @since 1.1.0
	 * @param string $message Success message.
	 * @param string $code Message code. Default empty.
	 * @return void
	 */
	public static function add_settings_success(string $message, string $code = ''): void
	{
		if (empty($message)) {
			return;
		}

		$code = !empty($code) ? sanitize_key($code) : 'dynos_general_success';

		add_settings_error(
			'Dynamic_Online_Services',
			$code,
			esc_html($message),
			'updated'
		);
	}

	/**
	 * Get a user-friendly error message for common errors.
	 *
	 * @since 1.1.0
	 * @param string $error_code Error code.
	 * @param array  $args Additional arguments for message formatting.
	 * @return string User-friendly error message.
	 */
	public static function get_user_friendly_error(string $error_code, array $args = []): string
	{
		$messages = [
			'invalid_post_id' => __('Invalid post ID provided. Please try again.', 'dynamic-online-services'),
			'invalid_term_id' => __('Invalid category ID provided. Please try again.', 'dynamic-online-services'),
			'invalid_attachment' => __('Invalid image selected. Please select a valid image.', 'dynamic-online-services'),
			'save_failed' => __('Failed to save changes. Please try again.', 'dynamic-online-services'),
			'permission_denied' => __('You do not have permission to perform this action.', 'dynamic-online-services'),
			'invalid_input' => __('Invalid input provided. Please check your entries and try again.', 'dynamic-online-services'),
			'network_error' => __('Network error occurred. Please check your connection and try again.', 'dynamic-online-services'),
		];

		$message = isset($messages[$error_code]) ? $messages[$error_code] : '';

		// Allow filtering the message
		$message = apply_filters('dynos_user_friendly_error_message', $message, $error_code, $args);

		return $message;
	}

	/**
	 * Add a validation warning admin notice.
	 * Centralized function to reduce code duplication for validation warnings.
	 *
	 * @since 1.1.0
	 * @param string $message The warning message to display.
	 * @param string $log_message Optional log message for WP_DEBUG. Default empty.
	 * @return void
	 */
	public static function add_validation_warning(string $message, string $log_message = ''): void
	{
		// Log warning in debug mode
		if (!empty($log_message) && defined('WP_DEBUG') && WP_DEBUG) {
			self::log_error($log_message, 'warning');
		}

		// Add admin notice for user feedback
		if (is_admin() && current_user_can('manage_options')) {
			add_action(
				'admin_notices',
				function () use ($message) {
					printf(
						'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
						wp_kses_post($message)
					);
				}
			);
		}
	}

	/**
	 * Centralized error logging function.
	 * Standardizes error logging across the plugin.
	 *
	 * @since 1.1.0
	 * @param string $message Error message to log.
	 * @param string $level Log level: 'error', 'warning', 'info', 'debug'. Default 'error'.
	 * @param array  $context Additional context data. Default empty array.
	 * @return void
	 */
	public static function log_error(string $message, string $level = 'error', array $context = []): void
	{
		if (!defined('WP_DEBUG') || !WP_DEBUG) {
			return;
		}

		// Validate log level
		$allowed_levels = ['error', 'warning', 'info', 'debug'];
		if (!in_array($level, $allowed_levels, true)) {
			$level = 'error';
		}

		// Format message with context
		$formatted_message = 'DYNOS [' . strtoupper($level) . ']: ' . $message;

		if (!empty($context)) {
			$formatted_message .= ' | Context: ' . wp_json_encode($context);
		}

		// Log the error
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log($formatted_message);

		// Fire action for extensibility
		do_action('dynos_log_error', $message, $level, $context);
	}
}
