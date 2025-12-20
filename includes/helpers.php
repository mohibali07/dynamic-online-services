<?php
/**
 * Helper Functions
 *
 * This file now acts as a loader for helper functions.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'dynos_escape_css_value' ) ) {
	function dynos_escape_css_value( $value, $property = '' ) {
		return \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value( $value, $property );
	}
}

if ( ! function_exists( 'dynos_validate_orderby' ) ) {
	function dynos_validate_orderby( $orderby, $default = 'date' ) {
		$allowed_orderby = array( 'date', 'modified', 'title', 'name', 'ID', 'rand', 'menu_order', 'author', 'post__in', 'none' );
		$orderby = strtolower( trim( (string)$orderby ) );
		return in_array( $orderby, $allowed_orderby, true ) ? $orderby : $default;
	}
}

if ( ! function_exists( 'dynos_log_error' ) ) {
	function dynos_log_error( $message, $level = 'error', $context = array() ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( 'DYNOS [%s]: %s %s', strtoupper( $level ), $message, json_encode( $context ) ) );
		}
	}
}

if ( ! function_exists( 'dynos_validate_post_object' ) ) {
	function dynos_validate_post_object( $post, $post_type ) {
		if ( is_numeric( $post ) ) { $post = get_post( (int)$post ); }
		if ( ! $post instanceof \WP_Post ) { return false; }
		if ( $post->post_type !== $post_type ) { return false; }
		return $post;
	}
}

if ( ! function_exists( 'dynos_validate_term_object' ) ) {
	function dynos_validate_term_object( $term, $taxonomy ) {
		if ( is_numeric( $term ) ) { $term = get_term( (int)$term, $taxonomy ); }
		if ( ! $term instanceof \WP_Term ) { return false; }
		if ( $term->taxonomy !== $taxonomy ) { return false; }
		return $term;
	}
}

// Include classes
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-images.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-acf.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-admin-notices.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-screen.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/class-shortcode-attributes.php';

if ( ! function_exists( 'dynos_validate_attachment_id' ) ) {
	function dynos_validate_attachment_id( $attachment_id ) {
		return \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::validate_attachment_id( $attachment_id );
	}
}

if ( ! function_exists( 'dynos_is_post_edit_screen' ) ) {
	function dynos_is_post_edit_screen( $post_type ) {
		return \TechmireSolutions\DynamicOnlineServices\Helpers\Screen::is_post_edit_screen( $post_type );
	}
}

