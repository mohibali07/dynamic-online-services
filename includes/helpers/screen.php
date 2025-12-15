<?php
/**
 * Screen Helper Functions
 *
 * Handles WordPress admin screen checking and validation.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if current screen matches the specified criteria.
 * Centralized function to reduce code duplication for screen checking.
 *
 * @since 1.1.0
 * @param string $base Screen base (e.g., 'post', 'term', 'edit-tags').
 * @param string $post_type Post type slug (optional).
 * @param string $taxonomy Taxonomy slug (optional).
 * @param string $action Screen action (optional, e.g., 'add').
 * @return bool True if screen matches, false otherwise.
 */
function dynos_is_current_screen( $base, $post_type = '', $taxonomy = '', $action = '' ): bool {
	$screen = get_current_screen();

	// Check if screen object exists (can be null in some contexts like AJAX)
	if ( ! $screen ) {
		return false;
	}

	// Check base
	if ( $screen->base !== $base ) {
		return false;
	}

	// Check post type if provided
	if ( ! empty( $post_type ) && isset( $screen->post_type ) && $screen->post_type !== $post_type ) {
		return false;
	}

	// Check taxonomy if provided
	if ( ! empty( $taxonomy ) && isset( $screen->taxonomy ) && $screen->taxonomy !== $taxonomy ) {
		return false;
	}

	// Check action if provided
	if ( ! empty( $action ) && isset( $screen->action ) && $screen->action !== $action ) {
		return false;
	}

	return true;
}

/**
 * Check if current screen is a taxonomy add form.
 *
 * @since 1.1.0
 * @param string $taxonomy Taxonomy slug.
 * @return bool True if on taxonomy add form, false otherwise.
 */
function dynos_is_taxonomy_add_screen( $taxonomy ): bool {
	return dynos_is_current_screen( 'term', '', $taxonomy, 'add' );
}

/**
 * Check if current screen is a taxonomy edit form.
 *
 * @since 1.1.0
 * @param string $taxonomy Taxonomy slug.
 * @return bool True if on taxonomy edit form, false otherwise.
 */
function dynos_is_taxonomy_edit_screen( $taxonomy ): bool {
	return dynos_is_current_screen( 'term', '', $taxonomy );
}

/**
 * Check if current screen is a post edit screen.
 *
 * @since 1.1.0
 * @param string $post_type Post type slug.
 * @return bool True if on post edit screen, false otherwise.
 */
function dynos_is_post_edit_screen( $post_type ): bool {
	return dynos_is_current_screen( 'post', $post_type );
}
