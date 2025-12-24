<?php
/**
 * Category Shortcode Validation
 *
 * Handles validation and sanitization of category shortcode attributes.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validate and sanitize category shortcode attributes.
 *
 * @since 1.1.0
 * @param array $atts Raw shortcode attributes.
 * @return array Validated and sanitized attributes.
 */
function dynos_validate_category_shortcode_attributes( $atts ): array {
	// Validate posts_per_page (must be integer, -1 for all, or positive number)
	$posts_per_page_raw = isset( $atts['posts_per_page'] ) ? $atts['posts_per_page'] : -1;
	if ( is_numeric( $posts_per_page_raw ) ) {
		$atts['posts_per_page'] = intval( $posts_per_page_raw );
		if ( $atts['posts_per_page'] < -1 ) {
			$atts['posts_per_page'] = -1;
		}
		// Prevent excessive queries that could impact performance
		if ( $atts['posts_per_page'] > \TechmireSolutions\DynamicOnlineServices\Core\Configuration::get_max_posts_per_page() ) {
			$atts['posts_per_page'] = \TechmireSolutions\DynamicOnlineServices\Core\Configuration::get_max_posts_per_page();
			dynos_log_error(
				sprintf(
					'posts_per_page value exceeded maximum (%d). Limited to %d for performance.',
					\TechmireSolutions\DynamicOnlineServices\Core\Configuration::get_max_posts_per_page(),
					\TechmireSolutions\DynamicOnlineServices\Core\Configuration::get_max_posts_per_page()
				),
				'warning',
				array( 'original_value' => $posts_per_page_raw )
			);
		}
	} else {
		$atts['posts_per_page'] = -1;
	}

	// Validate orderby (must be valid WordPress orderby value)
	$valid_orderby   = array( 'menu_order', 'date', 'title', 'ID', 'author', 'modified', 'rand' );
	$atts['orderby'] = isset( $atts['orderby'] ) ? sanitize_key( $atts['orderby'] ) : 'menu_order';
	if ( ! in_array( $atts['orderby'], $valid_orderby, true ) ) {
		$atts['orderby'] = 'menu_order';
	}

	// Validate order (must be ASC or DESC)
	$atts['order'] = isset( $atts['order'] ) ? strtoupper( sanitize_text_field( $atts['order'] ) ) : 'ASC';
	if ( ! in_array( $atts['order'], array( 'ASC', 'DESC' ), true ) ) {
		$atts['order'] = 'ASC';
	}

	// Validate hide_empty (must be boolean)
	$atts['hide_empty'] = isset( $atts['hide_empty'] ) ? filter_var( $atts['hide_empty'], FILTER_VALIDATE_BOOLEAN ) : false;

	// Validate columns (must be 'auto' or numeric string)
	$atts['columns'] = isset( $atts['columns'] ) ? sanitize_text_field( $atts['columns'] ) : 'auto';
	if ( 'auto' !== $atts['columns'] && ! is_numeric( $atts['columns'] ) ) {
		$atts['columns'] = 'auto';
	}

	// Validate min_width (must be valid CSS dimension value)
	$atts['min_width'] = isset( $atts['min_width'] ) ? sanitize_text_field( $atts['min_width'] ) : '';
	if ( ! empty( $atts['min_width'] ) ) {
		$atts['min_width'] = dynos_sanitize_css_value( $atts['min_width'], 'min-width' );
		// If sanitization results in empty value, reset to empty
		if ( empty( $atts['min_width'] ) ) {
			$atts['min_width'] = '';
		}
	}

	// Validate pagination (must be boolean)
	$atts['pagination'] = isset( $atts['pagination'] ) ? filter_var( $atts['pagination'], FILTER_VALIDATE_BOOLEAN ) : false;

	// Validate paged (must be positive integer)
	$atts['paged'] = isset( $atts['paged'] ) ? absint( $atts['paged'] ) : ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
	if ( empty( $atts['paged'] ) || $atts['paged'] < 1 ) {
		$atts['paged'] = 1;
	}

	return $atts;
}
