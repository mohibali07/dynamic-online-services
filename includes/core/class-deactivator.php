<?php
/**
 * Plugin Deactivator Class
 *
 * Handles plugin deactivation tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Deactivator class.
 */
class Deactivator
{
	/**
	 * Plugin deactivation hook.
	 *
	 * @since 1.1.0
	 */
	public static function deactivate(): void
	{
		try {
			// Flush rewrite rules
			flush_rewrite_rules();

			// Clear any transients
			delete_transient('dynos_plugin_activated');
			delete_transient('dynos_component_init_error');
			delete_transient('dynos_activation_error');
		} catch (\RuntimeException | \Exception $e) {
			// Log error but don't stop deactivation
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log(
					sprintf(
						'DYNOS Deactivation cleanup warning: %s',
						$e->getMessage()
					)
				);
			}
		}
	}
}
