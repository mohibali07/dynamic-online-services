<?php
/**
 * Category Shortcode Query Builder
 *
 * Handles building queries for category shortcode content.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get child categories for the current term.
 *
 * @since 1.1.0
 * @param WP_Term $term Current term object.
 * @param bool    $hide_empty Whether to hide empty categories.
 * @return array Array of category items to display.
 */
function dynos_get_category_shortcode_child_categories( $term, $hide_empty ): array {
	$items = array();

	$settings      = function_exists( 'dynos_sanitize_cpt_settings' ) ? dynos_sanitize_cpt_settings() : array();
	$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

	$child_categories = get_terms(
		array(
			'taxonomy'   => $taxonomy_slug,
			'parent'     => absint( $term->term_id ),
			'hide_empty' => filter_var( $hide_empty, FILTER_VALIDATE_BOOLEAN ),
		)
	);

	if ( ! is_wp_error( $child_categories ) && ! empty( $child_categories ) ) {
		foreach ( $child_categories as $category ) {
			$thumbnail_id = get_term_meta( $category->term_id, 'service_cat_thumbnail', true );
			$term_link    = get_term_link( $category );

			// Skip if term link is an error
			if ( is_wp_error( $term_link ) ) {
				dynos_log_error(
					sprintf(
						'Failed to get term link for term ID %d (%s): %s',
						$category->term_id,
						$category->name,
						$term_link->get_error_message()
					),
					'warning',
					array(
						'term_id'    => $category->term_id,
						'term_name'  => $category->name,
						'error_code' => $term_link->get_error_code(),
					)
				);
				do_action( 'dynos_category_content_term_link_error', $category, $term_link );
				continue;
			}

			$items[] = array(
				'type'        => 'category',
				'title'       => $category->name,
				'description' => wp_trim_words( $category->description, DYNOS_DEFAULT_EXCERPT_LENGTH ),
				'url'         => $term_link,
				'image_id'    => $thumbnail_id ? intval( $thumbnail_id ) : 0,
			);
		}
	}

	return $items;
}

/**
 * Get services for the current term.
 *
 * @since 1.1.0
 * @param WP_Term $term Current term object.
 * @param array   $atts Shortcode attributes.
 * @return array Array containing services and pagination info.
 */
function dynos_get_category_shortcode_services( $term, $atts ): array {
	$posts_per_page = $atts['posts_per_page'];
	$orderby        = $atts['orderby'];
	$order          = $atts['order'];
	$pagination     = $atts['pagination'];
	$paged          = $atts['paged'];

	$settings      = function_exists( 'dynos_sanitize_cpt_settings' ) ? dynos_sanitize_cpt_settings() : array();
	$service_slug  = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';
	$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

	// Use WP_Query for pagination support, or get_posts for backward compatibility
	$query_args = array(
		'post_type'      => $service_slug,
		'posts_per_page' => $posts_per_page > 0 ? $posts_per_page : -1,
		'orderby'        => $orderby,
		'order'          => $order,
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomy_slug,
				'field'            => 'term_id',
				'terms'            => absint( $term->term_id ),
				'include_children' => false,
			),
		),
		'post_status'    => 'publish',
		'no_found_rows'  => ! $pagination, // Only count found rows if pagination is enabled
	);

	// Add pagination parameters if enabled
	if ( $pagination && $posts_per_page > 0 ) {
		$query_args['paged']         = $paged;
		$query_args['no_found_rows'] = false;
	}

	// Allow filtering query arguments
	$query_args = apply_filters( 'dynos_category_content_query_args', $query_args, $term );

	if ( $pagination && $posts_per_page > 0 ) {
		// Use WP_Query for pagination support
		$services_query = new WP_Query( $query_args );
		$services       = $services_query->posts;
		$total_pages    = $services_query->max_num_pages;
		$current_page   = $paged;
	} else {
		// Use get_posts for backward compatibility (no pagination)
		$services     = get_posts( $query_args );
		$total_pages  = 0;
		$current_page = 1;
	}

	// Allow filtering services
	$services = apply_filters( 'dynos_category_content_services', $services, $term );

	// Convert services to display items
	$items = array();
	foreach ( $services as $service_post ) {
		// Get banner image and description using consolidated helper functions
		$image_array = dynos_get_service_banner_image( $service_post->ID );
		$description = dynos_get_service_description( $service_post->ID );

		$image_url = '';
		$image_alt = esc_attr( $service_post->post_title );

		if ( is_array( $image_array ) && isset( $image_array['url'] ) ) {
			$image_url = esc_url( $image_array['url'] );
			if ( isset( $image_array['alt'] ) && ! empty( $image_array['alt'] ) ) {
				$image_alt = esc_attr( $image_array['alt'] );
			}
		}

		$permalink = get_permalink( $service_post );

		// Skip if permalink is invalid
		if ( ! $permalink ) {
			continue;
		}

		$items[] = array(
			'type'        => 'post',
			'title'       => $service_post->post_title,
			'description' => wp_trim_words( $description, DYNOS_DEFAULT_EXCERPT_LENGTH ),
			'url'         => $permalink,
			'image_url'   => $image_url,
			'image_alt'   => $image_alt,
		);
	}

	return array(
		'items'        => $items,
		'total_pages'  => $total_pages,
		'current_page' => $current_page,
	);
}
