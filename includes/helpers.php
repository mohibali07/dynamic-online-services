<?php
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
function dynos_escape_css_value( $value, $property = '' ) {
	return \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value( $value, $property );
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
function dynos_validate_orderby( $orderby, $default = 'date' ) {
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
	$orderby = strtolower( trim( $orderby ) );

	// Check if in whitelist
	if ( in_array( $orderby, $allowed_orderby, true ) ) {
		return $orderby;
	}

	// Return default if not whitelisted
	return $default;
	// Return default if not whitelisted
	return $default;
}

/**
 * Log error message.
 *
 * @since 1.1.0
 * @param string $message Error message.
 * @param string $level Error level.
 * @param array  $context Additional context.
 */
function dynos_log_error( $message, $level = 'error', $context = array() ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( sprintf( 'DYNOS [%s]: %s %s', strtoupper( $level ), $message, json_encode( $context ) ) );
	}
}

/**
 * Validate post object.
 *
 * @since 1.1.0
 * @param mixed  $post Post object or ID.
 * @param string $post_type Expected post type.
 * @return WP_Post|false Post object or false if invalid.
 */
function dynos_validate_post_object( $post, $post_type ) {
	if ( is_numeric( $post ) ) {
		$post = get_post( $post );
	}

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( $post->post_type !== $post_type ) {
		return false;
	}

	return $post;
}

/**
 * Validate term object.
 *
 * @since 1.1.0
 * @param mixed  $term Term object or ID.
 * @param string $taxonomy Expected taxonomy.
 * @return WP_Term|false Term object or false if invalid.
 */
function dynos_validate_term_object( $term, $taxonomy ) {
	if ( is_numeric( $term ) ) {
		$term = get_term( $term, $taxonomy );
	}

	if ( ! $term instanceof WP_Term ) {
		return false;
	}

	if ( $term->taxonomy !== $taxonomy ) {
		return false;
	}

	return $term;
}

if (!defined('ABSPATH')) {
	exit;
}

// Include helper files (only files that actually exist)
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-images.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-acf.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-admin-notices.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-screen.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-shortcode-attributes.php';
