<?php
/**
 * ACF Helper Functions
 *
 * Handles ACF field retrieval with fallbacks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper function to get ACF field value with existence check.
 * Unified function that handles all ACF field retrieval cases.
 *
 * @since 1.1.0
 * @param string $field_name Field name or selector. Must be a valid string.
 * @param mixed  $post_id    Post ID (optional). If false or 0, uses current post. Must be a valid post ID if provided.
 * @param mixed  $default    Default value if ACF not available or field empty. Default empty string.
 * @return mixed Field value or default if ACF is not available or field is empty.
 */
function dynos_get_acf_field( $field_name, $post_id = false, $default = '' ): mixed {
	// Validate field name
	if ( empty( $field_name ) || ! is_string( $field_name ) ) {
		return $default;
	}

	// Check if ACF is available
	if ( ! function_exists( 'get_field' ) ) {
		// Log notice in debug mode that ACF is not available
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! has_action( 'admin_notices', 'dynos_acf_missing_notice' ) ) {
			add_action( 'admin_notices', 'dynos_acf_missing_notice' );
		}
		return $default;
	}

	// Validate post_id if provided
	if ( false !== $post_id && 0 !== $post_id ) {
		$post_id = absint( $post_id );
		if ( 0 === $post_id ) {
			return $default;
		}
		// Verify post exists
		if ( ! get_post( $post_id ) ) {
			return $default;
		}
	}

	// Get field value
	if ( false === $post_id || 0 === $post_id ) {
		$value = get_field( $field_name );
	} else {
		$value = get_field( $field_name, $post_id );
	}

	// Return value if not false/null, otherwise return default
	return false !== $value && null !== $value ? $value : $default;
}

/**
 * Alias for dynos_get_acf_field for backward compatibility.
 *
 * @since 1.1.0
 * @param string $selector Field selector.
 * @param mixed  $post_id  Post ID (optional).
 * @return mixed Field value or false if ACF is not available.
 */
function dynos_get_field( $selector, $post_id = false ): mixed {
	return dynos_get_acf_field( $selector, $post_id, false );
}

/**
 * Display admin notice when ACF is not available (only shown once per page load).
 *
 * @since 1.1.0
 * @return void
 */
function dynos_acf_missing_notice(): void {
	// Only show to administrators and only once
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if notice was already shown
	static $notice_shown = false;
	if ( $notice_shown ) {
		return;
	}
	$notice_shown = true;

	printf(
		'<div class="notice notice-info is-dismissible"><p>%s</p></div>',
		esc_html__( 'Dynamic Online Services: Advanced Custom Fields (ACF) plugin is not active. Some features that require ACF fields may not work as expected. The plugin will continue to function with default values.', 'dynamic-online-services' )
	);
}
