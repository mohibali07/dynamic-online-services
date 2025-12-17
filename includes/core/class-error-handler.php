<?php
/**
 * Error Handler
 *
 * Centralized error handling for the plugin.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handle plugin exceptions gracefully.
 *
 * Replaces direct wp_die() calls with proper exception handling.
 *
 * @since 1.1.0
 * @param \TechmireSolutions\DynamicOnlineServices\Core\PluginException $exception Exception object.
 * @param bool          $log_error Whether to log the error. Default true.
 * @return void
 */
function dynos_handle_exception(\TechmireSolutions\DynamicOnlineServices\Core\PluginException $exception, bool $log_error = true): void
{
	if ($log_error) {
		// Log error using centralized logging function
		if (function_exists('dynos_log_error')) {
			dynos_log_error(
				$exception->getMessage(),
				'error',
				array_merge(
					$exception->get_context(),
					[
						'error_code' => $exception->get_error_code(),
						'file' => $exception->getFile(),
						'line' => $exception->getLine(),
					]
				)
			);
		}
	}

	// Fire action for extensibility
	do_action('dynos_exception_handled', $exception);

	// In admin context, display error notice instead of wp_die
	if (is_admin()) {
		// Add admin notice
		if (function_exists('dynos_add_validation_warning_notice')) {
			$message = sprintf(
				/* translators: 1: Error message */
				esc_html__('Error: %s', 'dynamic-online-services'),
				$exception->getMessage()
			);
			dynos_add_validation_warning_notice($message, $exception->getMessage());
		}

		// Only use wp_die as last resort for critical errors
		if (500 === $exception->get_http_status_code()) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_die handles HTML output
			wp_die(
				esc_html($exception->getMessage()),
				esc_html__('Plugin Error', 'dynamic-online-services'),
				[
					'response' => absint($exception->get_http_status_code()),
					'back_link' => true,
				]
			);
		}
	} else {
		// Front-end: log and fail gracefully (no output)
		// Error is already logged above
	}
}

/**
 * Handle activation errors gracefully.
 *
 * @since 1.1.0
 * @param string $message Error message.
 * @param string $title Error title.
 * @param bool   $back_link Whether to show back link.
 * @return void
 */
function dynos_handle_activation_error(string $message, string $title = '', bool $back_link = true): void
{
	$title = !empty($title) ? $title : esc_html__('Plugin Activation Error', 'dynamic-online-services');

	// Log error
	if (function_exists('dynos_log_error')) {
		dynos_log_error($message, 'error', ['context' => 'activation']);
	}

	// Create exception
	$exception = new \TechmireSolutions\DynamicOnlineServices\Core\PluginException($message, 'activation_error', 0, [], 500);
	do_action('dynos_activation_error', $exception);

	// Use wp_die for activation errors (required by WordPress)
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_die handles HTML output
	wp_die(
		esc_html($message),
		esc_html($title),
		[
			'response' => 500,
			'back_link' => (bool) $back_link,
		]
	);
}

/**
 * Handle permission errors.
 *
 * @since 1.1.0
 * @param string $message Error message.
 * @param string $capability Required capability.
 * @return void
 */
function dynos_handle_permission_error(string $message = '', string $capability = 'manage_options'): void
{
	if (empty($message)) {
		$message = esc_html__('You do not have sufficient permissions to perform this action.', 'dynamic-online-services');
	}

	// Log error
	if (function_exists('dynos_log_error')) {
		dynos_log_error(
			$message,
			'error',
			[
				'context' => 'permission',
				'capability' => $capability,
				'current_user_id' => get_current_user_id(),
			]
		);
	}

	// Create exception
	$exception = new \TechmireSolutions\DynamicOnlineServices\Core\PluginException($message, 'permission_error', 403, ['capability' => $capability], 403);

	// Handle exception
	dynos_handle_exception($exception, false);
}
