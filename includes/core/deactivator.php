<?php
/**
 * Plugin Deactivator
 *
 * Handles plugin deactivation tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin deactivation hook.
 *
 * @since 1.1.0
 */
function dynos_deactivate_plugin(): void
{
	// Flush rewrite rules
	flush_rewrite_rules();

	// Clear any transients
	delete_transient('dynos_plugin_activated');
}
