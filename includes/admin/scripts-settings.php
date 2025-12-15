<?php
/**
 * Settings Admin Scripts
 *
 * Handles enqueuing of admin scripts for settings page.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enqueue admin scripts for settings page.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function dynos_enqueue_settings_admin_scripts($hook_suffix): void
{
	if ('settings_page_dynamic-online-services' !== $hook_suffix) {
		return;
	}

	// Check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}

	$script_path = DYNOS_PLUGIN_DIR . 'build/index.js';
	$script_asset_path = DYNOS_PLUGIN_DIR . 'build/index.asset.php';
	$script_url = DYNOS_PLUGIN_URL . 'build/index.js';

	if (file_exists($script_asset_path)) {
		$script_asset = require $script_asset_path;

		wp_enqueue_script(
			'dynos-settings-app',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		// Localize script for initial data using the correct handle
		wp_localize_script(
			'dynos-settings-app',
			'dynosSettings',
			array(
				'apiUrl' => esc_url_raw(rest_url('dynamic-online-services/v1/')),
				'nonce' => wp_create_nonce('wp_rest'),
			)
		);

		// Enqueue styles
		// wp-components stylesheet is required for the components to look right
		wp_enqueue_style('wp-components');

		if (file_exists(DYNOS_PLUGIN_DIR . 'build/index.css')) {
			wp_enqueue_style(
				'dynos-settings-app',
				DYNOS_PLUGIN_URL . 'build/index.css',
				array('wp-components'),
				$script_asset['version']
			);
		}
	}
}
add_action('admin_enqueue_scripts', 'dynos_enqueue_settings_admin_scripts');
