<?php
declare(strict_types=1);
/**
 * Helper Functions
 *
 * This file now acts as a loader for helper functions.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

/**
 * Escape CSS value for inline styles.
 *
 * @since 1.1.0
 * @param string $value CSS value.
 * @param string $property CSS property name (optional).
 * @return string Escaped CSS value.
 */
function dynos_escape_css_value($value, $property = '')
{
	return \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($value, $property);
}

/**
 * Validate and sanitize orderby parameter against whitelist.
 *
 * SECURITY: Prevents SQL injection by using whitelist instead of sanitize_sql_orderby.
 * sanitize_sql_orderby returns NULL on invalid input which can cause issues.
 *
 * @since 1.1.1
 * @param string $orderby Raw orderby value from user input.
 * @param string $default Default orderby value if invalid. Default 'date'.
 * @return string Safe orderby value.
 */
function dynos_validate_orderby($orderby, $default = 'date')
{
	// Whitelist of allowed orderby values for WP_Query
	$allowed_orderby = array(
		'date',
		'modified',
		'title',
		'name',
		'ID',
		'rand',
		'menu_order',
		'author',
		'post__in',
		'none',
	);

	// Convert to lowercase for case-insensitive comparison
	$orderby = strtolower(trim($orderby));

	// Check if in whitelist
	if (in_array($orderby, $allowed_orderby, true)) {
		return $orderby;
	}

	// Return default if not whitelisted
	return $default;
}


if (!defined('ABSPATH')) {
	exit;
}

// Include helper files
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-images.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-acf.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-admin-notices.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-screen.php';

require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-shortcode-attributes.php';
