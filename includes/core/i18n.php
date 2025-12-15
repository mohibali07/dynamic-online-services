<?php
/**
 * Internationalization
 *
 * Handles plugin textdomain loading for translations.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Load plugin textdomain for internationalization.
 *
 * @since 1.1.0
 */
function dynos_load_textdomain(): void
{
	load_plugin_textdomain(
		'dynamic-online-services',
		false,
		dirname(DYNOS_PLUGIN_BASENAME) . '/languages'
	);
}
add_action('plugins_loaded', 'dynos_load_textdomain');
