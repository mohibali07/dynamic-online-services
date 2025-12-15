<?php
/**
 * Category Shortcode Renderer
 *
 * Handles rendering of category shortcode HTML output.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render category shortcode items as HTML.
 *
 * @since 1.1.0
 * @param array   $items Items to display.
 * @param WP_Term $term  Current term object.
 * @param array   $atts  Shortcode attributes.
 * @return string HTML output.
 */
function dynos_render_category_shortcode_items($items, $term, $atts): string
{
	if (empty($items)) {
		$empty_message = apply_filters(
			'dynos_category_content_empty_message',
			__('No services or sub-categories found in this category.', 'dynamic-online-services'),
			$term
		);
		return '<p class="sos-no-content">' . esc_html($empty_message) . '</p>';
	}

	ob_start();

	// Get grid settings from options or shortcode attributes
	$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
	$grid_min_width = !empty($atts['min_width']) ? \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($atts['min_width'], 'min-width') : \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_min_width', DYNOS_DEFAULT_GRID_MIN_WIDTH);
	$grid_min_width = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_min_width, 'min-width');
	if (empty($grid_min_width)) {
		$grid_min_width = DYNOS_DEFAULT_GRID_MIN_WIDTH;
	}

	$grid_column_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_column_gap', '20px');
	$grid_row_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_row_gap', '40px');

	// Handle columns attribute
	$columns = $atts['columns'];
	if ('auto' !== $columns && is_numeric($columns)) {
		$columns = absint($columns);
		$columns = min(max($columns, DYNOS_MIN_GRID_COLUMNS), DYNOS_MAX_GRID_COLUMNS);
		$grid_style = 'grid-template-columns: repeat(' . absint($columns) . ', 1fr);';
	} else {
		// $grid_min_width is already sanitized via dynos_escape_css_value, safe for CSS
		$grid_style = 'grid-template-columns: repeat(auto-fit, minmax(' . $grid_min_width . ', 1fr));';
	}

	// Sanitize gap values (they come from options, already safe but double-check)
	$grid_column_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_column_gap, 'width');
	$grid_row_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_row_gap, 'height');
	if (empty($grid_column_gap)) {
		$grid_column_gap = '20px';
	}
	if (empty($grid_row_gap)) {
		$grid_row_gap = '40px';
	}

	$grid_class = apply_filters('dynos_category_content_grid_class', 'service-card-grid', $term);

	// Build inline style using dedicated style builder helper
	$grid_style_value = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_grid_style(
		array(
			'grid_style' => $grid_style,
			'column_gap' => $grid_column_gap,
			'row_gap' => $grid_row_gap,
		)
	);
	$grid_style_attr = !empty($grid_style_value) ? 'style="' . esc_attr($grid_style_value) . '"' : '';

	// Output with proper escaping
	echo '<ul class="' . esc_attr($grid_class) . '" ' . wp_kses_post($grid_style_attr) . '>';

	foreach ($items as $index => $item) {
		// Function returns safe HTML with internal escaping
		echo wp_kses_post(dynos_render_category_shortcode_item($item, $index, $term));
	}

	echo '</ul>';

	return ob_get_clean();
}

/**
 * Render a single category shortcode item.
 *
 * @since 1.1.0
 * @param array   $item  Item data.
 * @param int     $index Item index.
 * @param WP_Term $term  Current term object.
 * @return string HTML output for single item.
 */
function dynos_render_category_shortcode_item($item, $index, $term = null): string
{
	$image_url = '';
	$image_alt = '';

	if ('post' === $item['type']) {
		$image_url = isset($item['image_url']) ? $item['image_url'] : '';
		$image_alt = isset($item['image_alt']) ? $item['image_alt'] : '';
	} elseif ('category' === $item['type']) {
		if (!empty($item['image_id'])) {
			$attachment_id = absint($item['image_id']);
			if ($attachment_id > 0 && wp_attachment_is_image($attachment_id)) {
				$attachment_url = wp_get_attachment_image_url($attachment_id, 'full');
				if ($attachment_url) {
					$image_url = esc_url($attachment_url);
					$image_alt = esc_attr($item['title']);
				}
			}
		}
	}

	// Use placeholder if no image URL is available
	if (empty($image_url)) {
		$image_url = \TechmireSolutions\DynamicOnlineServices\Helpers\Images::get_placeholder_url();

		if (empty($image_alt) && isset($item['title'])) {
			$image_alt = esc_attr($item['title']);
		}
	}

	// Allow filtering individual item before rendering
	$item = apply_filters('dynos_category_content_item', $item, $index, $term);

	// Ensure required fields exist after filtering
	if (! is_array($item) || ! isset($item['type']) || ! isset($item['url']) || ! isset($item['title'])) {
		return '';
	}

	$card_class = apply_filters('dynos_category_content_card_class', 'service-card', $item, $index);
	$item_type = esc_attr($item['type']);
	$item_title = esc_html($item['title']);
	$item_type_label = 'category' === $item_type ? _x('category', 'Item type label for accessibility', 'dynamic-online-services') : _x('service', 'Item type label for accessibility', 'dynamic-online-services');
	$aria_label = sprintf(
		/* translators: 1: Item type (category or service), 2: Item title */
		esc_attr__('View %1$s: %2$s', 'dynamic-online-services'),
		esc_attr($item_type_label),
		esc_attr($item['title'])
	);

	ob_start();
	echo '<li class="' . esc_attr($card_class) . '" data-item-type="' . esc_attr($item_type) . '">';
	echo '<a href="' . esc_url($item['url']) . '" class="service-card-image-link" aria-label="' . esc_attr($aria_label) . '">';
	echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="service-card-image" loading="lazy" width="400" height="232">';
	echo '</a>';
	echo '<div class="service-card-content">';
	echo '<h3 class="service-card-title"><a href="' . esc_url($item['url']) . '" aria-label="' . esc_attr($aria_label) . '">' . esc_html($item_title) . '</a></h3>';
	if (!empty($item['description'])) {
		echo '<p class="service-card-description">' . esc_html($item['description']) . '</p>';
	}
	$button_text = apply_filters('dynos_category_content_button_text', __('VIEW DETAILS', 'dynamic-online-services'), $item);
	echo '<a href="' . esc_url($item['url']) . '" class="service-card-link" aria-label="' . esc_attr($aria_label) . '">' . esc_html($button_text) . '</a>';
	echo '</div></li>';

	return ob_get_clean();
}
