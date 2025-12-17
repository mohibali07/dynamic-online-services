<?php
/**
 * Permalink Handler
 *
 * Handles custom permalink structure for service posts with category hierarchy.
 *
 * @package Dynamic_Online_Services
 * @subpackage Post_Types
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Delegate to the new class-based handler
add_filter( 'post_type_link', [ '\TechmireSolutions\DynamicOnlineServices\PostTypes\PermalinkHandler', 'filter_post_type_link' ], 10, 2 );

// Backward compatibility for helper functions if they were used elsewhere
// (Though implementation plan suggested they are internal helpers, keeping them as wrappers if needed or just removing them is fine. Plan said deprecate/delegate)

if ( ! function_exists( __NAMESPACE__ . '\\service_permalink_structure' ) ) {
	/**
	 * Deprecated: Use PermalinkHandler::filter_post_type_link instead.
	 *
	 * @deprecated 1.2.0
	 * @param string  $post_link The post permalink.
	 * @param \WP_Post $post      The post object.
	 * @return string Modified post permalink.
	 */
	function service_permalink_structure( $post_link, $post ): string {
		return PermalinkHandler::filter_post_type_link( $post_link, $post );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\\build_category_hierarchy_path' ) ) {
	/**
	 * Deprecated: Use PermalinkHandler::build_category_hierarchy_path instead.
	 *
	 * @deprecated 1.2.0
	 * @param \WP_Term $current_term   Current term object.
	 * @param string  $taxonomy_slug Taxonomy slug for term validation.
	 * @return string Category path.
	 */
	function build_category_hierarchy_path( \WP_Term $current_term, string $taxonomy_slug ): string {
		return PermalinkHandler::build_category_hierarchy_path( $current_term, $taxonomy_slug );
	}
}
