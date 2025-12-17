<?php
/**
 * Category Query Service
 *
 * Handles fetching categories and services for the category shortcode.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

use TechmireSolutions\DynamicOnlineServices\Helpers\Images;
use TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices;
use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization;
use WP_Query;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Category Query Service class.
 */
class CategoryQueryService {

	/**
	 * Get child categories for the current term.
	 *
	 * @since 1.2.0
	 * @param WP_Term $term Current term object.
	 * @param bool    $hide_empty Whether to hide empty categories.
	 * @return array Array of category items to display.
	 */
	public static function get_child_categories( WP_Term $term, bool $hide_empty ): array {
		$items = [];

		$settings = Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

		$child_categories = get_terms(
			[
				'taxonomy'   => $taxonomy_slug,
				'parent'     => absint( $term->term_id ),
				'hide_empty' => $hide_empty,
			]
		);

		if ( ! is_wp_error( $child_categories ) && ! empty( $child_categories ) ) {
			// FIX: Batch-fetch all term meta to avoid N+1 query problem
			$term_ids = wp_list_pluck( $child_categories, 'term_id' );
			$thumbnails_map = [];

			if ( ! empty( $term_ids ) ) {
				global $wpdb;
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$results = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT term_id, meta_value FROM {$wpdb->termmeta}
						WHERE term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
						AND meta_key = %s",
						'service_cat_thumbnail'
					)
				);

				foreach ( $results as $row ) {
					$thumbnails_map[ $row->term_id ] = $row->meta_value;
				}
			}

			foreach ( $child_categories as $category ) {
				/** @var WP_Term $category */

				$thumbnail_id = isset( $thumbnails_map[ $category->term_id ] ) ? $thumbnails_map[ $category->term_id ] : '';
				$term_link    = get_term_link( $category );

				if ( is_wp_error( $term_link ) ) {
					if ( class_exists( AdminNotices::class ) ) {
						AdminNotices::log_error(
							sprintf(
								'Failed to get term link for term ID %d (%s): %s',
								$category->term_id,
								$category->name,
								$term_link->get_error_message()
							),
							'warning',
							[
								'term_id'    => $category->term_id,
								'term_name'  => $category->name,
								'error_code' => $term_link->get_error_code(),
							]
						);
					}
					do_action( 'dynos_category_content_term_link_error', $category, $term_link );
					continue;
				}

				$items[] = [
					'type'        => 'category',
					'title'       => $category->name,
					'description' => wp_trim_words( $category->description, DYNOS_DEFAULT_EXCERPT_LENGTH ),
					'url'         => $term_link,
					'image_id'    => $thumbnail_id ? intval( $thumbnail_id ) : 0,
				];
			}
		}

		return $items;
	}

	/**
	 * Get services for the current term.
	 *
	 * @since 1.2.0
	 * @param WP_Term $term Current term object.
	 * @param array   $atts Shortcode attributes.
	 * @return array Array containing services and pagination info.
	 */
	public static function get_services( WP_Term $term, array $atts ): array {
		$posts_per_page = isset($atts['posts_per_page']) ? (int) $atts['posts_per_page'] : -1;
		$orderby        = isset($atts['orderby']) ? $atts['orderby'] : 'menu_order';
		$order          = isset($atts['order']) ? $atts['order'] : 'ASC';
		$pagination     = isset($atts['pagination']) && filter_var($atts['pagination'], FILTER_VALIDATE_BOOLEAN);
		$paged          = isset($atts['paged']) ? (int) $atts['paged'] : 1;

		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug  = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';
		$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

		$query_args = [
			'post_type'      => $service_slug,
			'posts_per_page' => $posts_per_page > 0 ? $posts_per_page : -1,
			'orderby'        => $orderby,
			'order'          => $order,
			'tax_query'      => [
				[
					'taxonomy'         => $taxonomy_slug,
					'field'            => 'term_id',
					'terms'            => absint( $term->term_id ),
					'include_children' => false,
				],
			],
			'post_status'    => 'publish',
			'no_found_rows'  => ! $pagination,
		];

		if ( $pagination && $posts_per_page > 0 ) {
			$query_args['paged']         = $paged;
			$query_args['no_found_rows'] = false;
		}

		$query_args = apply_filters( 'dynos_category_content_query_args', $query_args, $term );

		if ( $pagination && $posts_per_page > 0 ) {
			$services_query = new WP_Query( $query_args );
			$services       = $services_query->posts;
			$total_pages    = $services_query->max_num_pages;
			$current_page   = $paged;
		} else {
			$services     = get_posts( $query_args );
			$total_pages  = 0;
			$current_page = 1;
		}

		$services = apply_filters( 'dynos_category_content_services', $services, $term );

		$items = [];
		foreach ( $services as $service_post ) {
			$image_array = Images::get_service_banner_image( $service_post->ID );
			$description = Images::get_service_description( $service_post->ID );

			$image_url = '';
			$image_alt = esc_attr( $service_post->post_title );

			if ( is_array( $image_array ) && isset( $image_array['url'] ) ) {
				$image_url = esc_url( $image_array['url'] );
				if ( isset( $image_array['alt'] ) && ! empty( $image_array['alt'] ) ) {
					$image_alt = esc_attr( $image_array['alt'] );
				}
			}

			$permalink = get_permalink( $service_post );

			if ( ! $permalink ) {
				continue;
			}

			$items[] = [
				'type'        => 'post',
				'title'       => $service_post->post_title,
				'description' => wp_trim_words( $description, DYNOS_DEFAULT_EXCERPT_LENGTH ),
				'url'         => $permalink,
				'image_url'   => $image_url,
				'image_alt'   => $image_alt,
			];
		}

		return [
			'items'        => $items,
			'total_pages'  => $total_pages,
			'current_page' => $current_page,
		];
	}
}
