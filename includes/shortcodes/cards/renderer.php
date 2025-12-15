<?php
/**
 * Cards Shortcode Renderer
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render cards grid.
 *
 * @since 1.1.0
 * @param array $items Items to display.
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function dynos_render_cards_grid($items, $atts): string
{
	if (empty($items)) {
		return '';
	}

	// Reuse existing grid logic or duplicate relevant parts to ensure independence but consistency
	// We will leverage the Logic from category/renderer.php but adapted for generic usage

	ob_start();

	// Get grid settings
	$options = \DynamicOnlineServices\Helpers\Options::get();

	// Allow shortcode to override min-width
	$grid_min_width = !empty($atts['min_width']) ? \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($atts['min_width'], 'min-width') : \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_min_width', DYNOS_DEFAULT_GRID_MIN_WIDTH);
	$grid_min_width = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_min_width, 'min-width');
	if (empty($grid_min_width)) {
		$grid_min_width = DYNOS_DEFAULT_GRID_MIN_WIDTH;
	}

	$grid_column_gap = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_column_gap', '20px');
	$grid_row_gap = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_row_gap', '40px');

	// Handle columns attribute
	$columns = $atts['columns'];
	if ('auto' !== $columns && is_numeric($columns)) {
		$columns = absint($columns);
		$columns = min(max($columns, DYNOS_MIN_GRID_COLUMNS), DYNOS_MAX_GRID_COLUMNS);
		$grid_style = 'grid-template-columns: repeat(' . absint($columns) . ', 1fr);';
	} else {
		$grid_style = 'grid-template-columns: repeat(auto-fit, minmax(' . $grid_min_width . ', 1fr));';
	}

	// Sanitize gaps
	$grid_column_gap = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_column_gap, 'width');
	$grid_row_gap = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_row_gap, 'height');

	// Build inline style
	$grid_style_value = '';
	if (class_exists('\\DynamicOnlineServices\\Helpers\\StyleBuilder')) {
		$grid_style_value = \DynamicOnlineServices\Helpers\StyleBuilder::build_grid_style(
			array(
				'grid_style' => $grid_style,
				'column_gap' => $grid_column_gap,
				'row_gap' => $grid_row_gap,
			)
		);
	} else {
		// Fallback if helper doesn't exist
		$grid_style_value = $grid_style . ' gap: ' . $grid_row_gap . ' ' . $grid_column_gap . ';';
	}

	$grid_style_attr = !empty($grid_style_value) ? 'style="' . esc_attr($grid_style_value) . '"' : '';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attribute already escaped
	echo '<ul class="service-card-grid doc-service-cards" ' . $grid_style_attr . '>';

	foreach ($items as $index => $item) {
		// Reuse the category item renderer if available, otherwise replicate logic
		if (function_exists('dynos_render_category_shortcode_item')) {
			// Mock a term object if needed, or pass null. The function expects a term for filters but might handle null.
			// Let's check the signature: dynos_render_category_shortcode_item($item, $index, $term)
			// It uses $term in filters. We can pass null or a dummy object.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Function handles escaping internally
			echo dynos_render_category_shortcode_item($item, $index, null);
		} else {
			// Fallback rendering
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Function handles escaping internally
			echo dynos_render_fallback_card_item($item);
		}
	}

	echo '</ul>';

	// Pagination
	if ('true' === $atts['show_pagination']) {
		// Implement pagination if needed, or simple next/prev
		// For now, basic numeric pagination handles are outside this scope unless requested,
		// relying on standard WP functions or the category pagination helper if adaptable.
	}

	return ob_get_clean();
}

/**
 * Fallback card renderer.
 */
function dynos_render_fallback_card_item($item): string
{
	$image_url = !empty($item['image_url']) ? $item['image_url'] : dynos_get_placeholder_image_url();

	ob_start();
	?>
	<li class="service-card type-post">
		<a href="<?php echo esc_url($item['url']); ?>" class="service-card-image-link">
			<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['image_alt']); ?>"
				class="service-card-image" loading="lazy">
		</a>
		<div class="service-card-content">
			<h3 class="service-card-title"><a
					href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a></h3>
			<?php if (!empty($item['description'])): ?>
				<p class="service-card-description"><?php echo esc_html($item['description']); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url($item['url']); ?>"
				class="service-card-link"><?php esc_html_e('VIEW DETAILS', 'dynamic-online-services'); ?></a>
		</div>
	</li>
	<?php
	return ob_get_clean();
}
