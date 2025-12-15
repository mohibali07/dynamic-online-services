<?php
/**
 * Plugin Loader
 *
 * Handles loading of all plugin files.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Load all required plugin files.
 *
 * @since 1.1.0
 */
function dynos_load_plugin_files(): void
{
	// Include core error handling first (order matters)
	require_once DYNOS_PLUGIN_DIR . 'includes/core/class-plugin-exception.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/core/class-error-handler.php';

	// Include required files (order matters - settings must come before helpers)
	require_once DYNOS_PLUGIN_DIR . 'includes/settings-registration.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/settings-renderer.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/helpers.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/post-types/class-service-post-type.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/taxonomy-fields.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/faqs/meta-box.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/class-faqs.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/class-category.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/class-hero.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/settings-page.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/front-end-scripts.php';
	require_once DYNOS_PLUGIN_DIR . 'includes/admin-scripts.php';

	// Display activation error notice if activation failed (after helpers are loaded)
	add_action('admin_notices', 'dynos_display_activation_error_notice');
}

/**
 * Display activation error notice if activation failed.
 *
 * @since 1.1.0
 */
function dynos_display_activation_error_notice(): void
{
	$error_message = get_transient('dynos_activation_error');
	if ($error_message) {
		delete_transient('dynos_activation_error');
		if (class_exists('\TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices')) {
			\TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices::error($error_message);
		} else {
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				wp_kses_post($error_message)
			);
		}
	}
}
