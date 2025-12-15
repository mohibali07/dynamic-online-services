<?php
/**
 * Category Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Category class.
 */
class Category {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'service_category_content';

	/**
	 * Initialize shortcode.
	 */
	public static function init(): void {
		add_shortcode( self::TAG, array( __CLASS__, 'render_callback' ) );
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_callback( $atts ): string {
		$instance = new self();
		return $instance->render( is_array( $atts ) ? $atts : array() );
	}

	/**
	 * Render shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render( array $atts ): string {
		// Load dependencies
		$this->load_dependencies();

		// Parse attributes
		$atts = shortcode_atts(
			array(
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'hide_empty'     => false,
				'columns'        => 'auto',
				'min_width'      => '',
				'pagination'     => false,
				'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			),
			$atts,
			self::TAG
		);

		// Validate attributes
		if ( function_exists( 'dynos_validate_category_shortcode_attributes' ) ) {
			$atts = dynos_validate_category_shortcode_attributes( $atts );
		}

		$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

		if ( ! is_tax( $taxonomy_slug ) ) {
			return '';
		}

		$term = get_queried_object();
		if ( function_exists( 'dynos_validate_term_object' ) ) {
			$term = dynos_validate_term_object( $term, $taxonomy_slug );
		}

		if ( ! $term ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				do_action( 'dynos_category_content_term_error', get_queried_object() );
			}
			return '';
		}

		$term = apply_filters( 'dynos_category_content_term', $term );

		// Enqueue styles
		\TechmireSolutions\DynamicOnlineServices\Helpers\AssetEnqueuer::enqueue_service_card_styles();

		return $this->generate_output( $term, $atts );
	}

	/**
	 * Generate HTML output.
	 *
	 * @param \WP_Term $term Category term.
	 * @param array    $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	protected function generate_output( $term, array $atts ): string {
		// Get data
		$child_categories = function_exists( 'dynos_get_category_shortcode_child_categories' )
			? dynos_get_category_shortcode_child_categories( $term, $atts['hide_empty'] )
			: array();

		$services_data = function_exists( 'dynos_get_category_shortcode_services' )
			? dynos_get_category_shortcode_services( $term, $atts )
			: array(
				'items'        => array(),
				'total_pages'  => 1,
				'current_page' => 1,
			);

		$service_items = $services_data['items'];
		$total_pages   = $services_data['total_pages'];
		$current_page  = $services_data['current_page'];

		$items_to_display = array_merge( $child_categories, $service_items );
		$items_to_display = apply_filters( 'dynos_category_content_items', $items_to_display, $term );

		// Render
		$output = '';
		if ( function_exists( 'dynos_render_category_shortcode_items' ) ) {
			$output = dynos_render_category_shortcode_items( $items_to_display, $term, $atts );
		}

		// Pagination
		$pagination_html = '';
		if ( $atts['pagination'] && $atts['posts_per_page'] > 0 && $total_pages > 1 ) {
			if ( function_exists( 'dynos_get_pagination_html' ) ) {
				$pagination_html = dynos_get_pagination_html( $current_page, $total_pages );
				$pagination_html = apply_filters( 'dynos_category_content_pagination', $pagination_html, $current_page, $total_pages, $term );
			}
		}

		$final_output = $output . $pagination_html;
		return apply_filters( 'dynos_category_content_output', $final_output, $items_to_display, $term );
	}

	/**
	 * Load required dependencies.
	 */
	protected function load_dependencies(): void {
		if ( defined( 'DYNOS_PLUGIN_DIR' ) ) {
			if ( ! function_exists( 'dynos_validate_category_shortcode_attributes' ) ) {
				require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/category/validation.php';
			}
			if ( ! function_exists( 'dynos_get_category_shortcode_child_categories' ) ) {
				require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/category/query.php';
			}
			if ( ! function_exists( 'dynos_render_category_shortcode_items' ) ) {
				require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/category/renderer.php';
			}
			if ( ! function_exists( 'dynos_get_pagination_html' ) ) {
				require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/category/pagination.php';
			}
		}
	}
}
