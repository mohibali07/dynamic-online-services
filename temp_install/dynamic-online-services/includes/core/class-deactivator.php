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
		// Flush rewrite rules
		flush_rewrite_rules();

		// Clear any transients
		delete_transient('dynos_plugin_activated');
	}
}
