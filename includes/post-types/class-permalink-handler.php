<?php
/**
 * Permalink Handler Class
 *
 * Handles custom permalink structure for service posts with category hierarchy.
 *
 * @package Dynamic_Online_Services
 * @subpackage PostTypes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\PostTypes;

use WP_Post;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Permalink Handler class.
 */
class PermalinkHandler {

	/**
	 * Replace the %services_category% placeholder in post permalink structure.
	 *
	 * @since 1.2.0
	 * @param string  $post_link The post permalink.
	 * @param WP_Post $post      The post object.
	 * @return string Modified post permalink.
	 */
	public static function filter_post_type_link( $post_link, $post ): string {
		$settings      = Sanitization::sanitize_cpt_settings();
		$service_slug  = $settings['service_slug'] ?? 'service';
		$taxonomy_slug = $settings['taxonomy_slug'] ?? 'services_category';

		// Validate post object
		if ( ! $post instanceof WP_Post ) {
			$post = get_post( $post );
		}
		if ( ! $post instanceof WP_Post || $post->post_type !== $service_slug ) {
			return $post_link;
		}

		if ( false === strpos( $post_link, '%services_category%' ) ) {
			return $post_link;
		}

		$post_link = apply_filters( 'dynos_service_permalink_before_process', $post_link, $post );

		$terms = wp_get_object_terms(
			$post->ID,
			$taxonomy_slug,
			[
				'orderby' => 'parent',
				'order'   => 'ASC',
			]
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			$post_link = str_replace( '%services_category%/', '', $post_link );
			if ( is_wp_error( $terms ) && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				do_action( 'dynos_permalink_term_error', $terms, $post->ID );
			}
			return $post_link;
		}

		$current_term = $terms[0];
		if ( ! $current_term instanceof WP_Term || $current_term->taxonomy !== $taxonomy_slug ) {
			$post_link = str_replace( '%services_category%/', '', $post_link );
			return $post_link;
		}

		$term_path = self::build_category_hierarchy_path( $current_term, $taxonomy_slug );

		$post_link = str_replace( '%services_category%', $term_path, $post_link );

		return apply_filters( 'dynos_service_permalink_after_process', $post_link, $post, $term_path );
	}

	/**
	 * Build category hierarchy path for permalink.
	 *
	 * @since 1.2.0
	 * @param WP_Term $current_term   Current term object.
	 * @param string  $taxonomy_slug Taxonomy slug for term validation.
	 * @return string Category path (e.g., 'parent/child/term').
	 */
	public static function build_category_hierarchy_path( WP_Term $current_term, string $taxonomy_slug ): string {
		$term_path_parts = [];

		$parent_id   = $current_term->parent;
		$max_depth   = defined( 'DYNOS_MAX_TAXONOMY_DEPTH' ) ? DYNOS_MAX_TAXONOMY_DEPTH : 10;
		$depth       = 0;
		$visited_ids = [ $current_term->term_id ];

		if ( $parent_id === $current_term->term_id ) {
			self::log_error( 'Self-reference detected', [ 'term_id' => $current_term->term_id ] );
			do_action( 'dynos_permalink_self_reference', $current_term->term_id );
			$parent_id = 0;
		}

		while ( 0 !== $parent_id && $depth < $max_depth ) {
			if ( in_array( $parent_id, $visited_ids, true ) ) {
				self::log_error( 'Circular reference detected', [
					'term_id'     => $current_term->term_id,
					'parent_id'   => $parent_id,
					'visited_ids' => $visited_ids,
				] );
				do_action( 'dynos_permalink_circular_reference', $current_term->term_id, $parent_id, $visited_ids );
				break;
			}

			$visited_ids[] = $parent_id;
			$parent_term   = get_term( $parent_id, $taxonomy_slug );

			if ( is_wp_error( $parent_term ) || ! $parent_term ) {
				if ( is_wp_error( $parent_term ) && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					do_action( 'dynos_permalink_parent_term_error', $parent_term, $parent_id );
				}
				break;
			}

			array_unshift( $term_path_parts, $parent_term->slug );
			$parent_id = $parent_term->parent;
			++$depth;
		}

		if ( $depth >= $max_depth ) {
			self::log_error( 'Maximum hierarchy depth reached', [
				'term_id'   => $current_term->term_id,
				'max_depth' => $max_depth,
			] );
			do_action( 'dynos_permalink_max_depth_reached', $current_term->term_id, $max_depth );
		}

		$term_path_parts[] = $current_term->slug;
		return implode( '/', $term_path_parts );
	}

	/**
	 * Log error helper.
	 *
	 * @param string $message Error message.
	 * @param array  $context Context data.
	 */
	private static function log_error( string $message, array $context = [] ): void {
		if ( function_exists( 'dynos_log_error' ) ) {
			dynos_log_error( $message, 'warning', $context );
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'DYNOS Permalink Error: ' . $message . ' ' . json_encode( $context ) );
		}
	}
}
