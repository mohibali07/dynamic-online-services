<?php
/**
 * Category Renderer
 *
 * Handles rendering of category shortcode HTML output.
 *
 * @package Dynamic_Online_Services
 * @subpackage Renderers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Renderers;

use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;
use TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder;
use TechmireSolutions\DynamicOnlineServices\Helpers\Images;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Category Renderer class.
 */
class CategoryRenderer {

	/**
	 * Render category shortcode items as HTML.
	 *
	 * @since 1.2.0
	 * @param array   $items Items to display.
	 * @param WP_Term $term  Current term object.
	 * @param array   $atts  Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render( array $items, WP_Term $term, array $atts ): string {
		if ( empty( $items ) ) {
			$empty_message = apply_filters(
				'dynos_category_content_empty_message',
				__( 'No services or sub-categories found in this category.', 'dynamic-online-services' ),
				$term
			);
			return '<p class="dynos-no-content">' . esc_html( $empty_message ) . '</p>';
		}

		ob_start();

		$options = Options::get();
		$grid_min_width = ! empty( $atts['min_width'] ) ? Sanitization::escape_css_value( $atts['min_width'], 'min-width' ) : Options::get_option( $options, 'card_grid_min_width', DYNOS_DEFAULT_GRID_MIN_WIDTH );
		$grid_min_width = Sanitization::escape_css_value( $grid_min_width, 'min-width' );
		if ( empty( $grid_min_width ) ) {
			$grid_min_width = DYNOS_DEFAULT_GRID_MIN_WIDTH;
		}

		$grid_column_gap = Options::get_option( $options, 'card_grid_column_gap', '20px' );
		$grid_row_gap    = Options::get_option( $options, 'card_grid_row_gap', '40px' );

		$columns = isset($atts['columns']) ? $atts['columns'] : 'auto';
		if ( 'auto' !== $columns && is_numeric( $columns ) ) {
			$columns = absint( $columns );
			$columns = min( max( $columns, DYNOS_MIN_GRID_COLUMNS ), DYNOS_MAX_GRID_COLUMNS );
			$grid_style = 'grid-template-columns: repeat(' . absint( $columns ) . ', 1fr);';
		} else {
			$grid_style = 'grid-template-columns: repeat(auto-fit, minmax(' . $grid_min_width . ', 1fr));';
		}

		$grid_column_gap = Sanitization::escape_css_value( $grid_column_gap, 'width' );
		$grid_row_gap    = Sanitization::escape_css_value( $grid_row_gap, 'height' );
		if ( empty( $grid_column_gap ) ) {
			$grid_column_gap = '20px';
		}
		if ( empty( $grid_row_gap ) ) {
			$grid_row_gap = '40px';
		}

		$grid_class = apply_filters( 'dynos_category_content_grid_class', 'service-card-grid', $term );

		$grid_style_value = StyleBuilder::build_grid_style(
			[
				'grid_style' => $grid_style,
				'column_gap' => $grid_column_gap,
				'row_gap'    => $grid_row_gap,
			]
		);
		$grid_style_attr = ! empty( $grid_style_value ) ? 'style="' . esc_attr( $grid_style_value ) . '"' : '';

		echo '<ul class="' . esc_attr( $grid_class ) . '" ' . wp_kses_post( $grid_style_attr ) . '>';

		foreach ( $items as $index => $item ) {
			echo wp_kses_post( $this->render_item( $item, $index, $term ) );
		}

		echo '</ul>';

		return ob_get_clean();
	}

	/**
	 * Render a single category shortcode item.
	 *
	 * @since 1.2.0
	 * @param array   $item  Item data.
	 * @param int     $index Item index.
	 * @param WP_Term $term  Current term object.
	 * @return string HTML output for single item.
	 */
	protected function render_item( array $item, int $index, ?WP_Term $term = null ): string {
		$image_url = '';
		$image_alt = '';

		if ( 'post' === $item['type'] ) {
			$image_url = isset( $item['image_url'] ) ? $item['image_url'] : '';
			$image_alt = isset( $item['image_alt'] ) ? $item['image_alt'] : '';
		} elseif ( 'category' === $item['type'] ) {
			if ( ! empty( $item['image_id'] ) ) {
				$attachment_id = absint( $item['image_id'] );
				if ( $attachment_id > 0 && wp_attachment_is_image( $attachment_id ) ) {
					$attachment_url = wp_get_attachment_image_url( $attachment_id, 'full' );
					if ( $attachment_url ) {
						$image_url = esc_url( $attachment_url );
						$image_alt = esc_attr( $item['title'] );
					}
				}
			}
		}

		if ( empty( $image_url ) ) {
			$image_url = Images::get_placeholder_url();

			if ( empty( $image_alt ) && isset( $item['title'] ) ) {
				$image_alt = esc_attr( $item['title'] );
			}
		}

		$item = apply_filters( 'dynos_category_content_item', $item, $index, $term );

		if ( ! is_array( $item ) || ! isset( $item['type'] ) || ! isset( $item['url'] ) || ! isset( $item['title'] ) ) {
			return '';
		}

		$card_class = apply_filters( 'dynos_category_content_card_class', 'service-card', $item, $index );
		$item_type = esc_attr( $item['type'] );
		$item_title = esc_html( $item['title'] );
		$item_type_label = 'category' === $item_type ? _x( 'category', 'Item type label for accessibility', 'dynamic-online-services' ) : _x( 'service', 'Item type label for accessibility', 'dynamic-online-services' );
		$aria_label = sprintf(
			esc_attr__( 'View %1$s: %2$s', 'dynamic-online-services' ),
			esc_attr( $item_type_label ),
			esc_attr( $item['title'] )
		);

		ob_start();
		echo '<li class="' . esc_attr( $card_class ) . '" data-item-type="' . esc_attr( $item_type ) . '">';
		echo '<a href="' . esc_url( $item['url'] ) . '" class="service-card-image-link" aria-label="' . esc_attr( $aria_label ) . '">';
		echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" class="service-card-image" loading="lazy" width="400" height="232">';
		echo '</a>';
		echo '<div class="service-card-content">';
		echo '<h3 class="service-card-title"><a href="' . esc_url( $item['url'] ) . '" aria-label="' . esc_attr( $aria_label ) . '">' . esc_html( $item_title ) . '</a></h3>';
		if ( ! empty( $item['description'] ) ) {
			echo '<p class="service-card-description">' . esc_html( $item['description'] ) . '</p>';
		}
		$button_text = apply_filters( 'dynos_category_content_button_text', __( 'VIEW DETAILS', 'dynamic-online-services' ), $item );
		echo '<a href="' . esc_url( $item['url'] ) . '" class="service-card-link" aria-label="' . esc_attr( $aria_label ) . '">' . esc_html( $button_text ) . '</a>';
		echo '</div></li>';

		return ob_get_clean();
	}
}
