<?php
/**
 * Cards Renderer Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Renderers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Renderers;

use TechmireSolutions\DynamicOnlineServices\Interfaces\RendererInterface;
use TechmireSolutions\DynamicOnlineServices\Services\SettingsService;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class CardsRenderer
 */
class CardsRenderer implements RendererInterface
{

	/**
	 * Render the cards grid.
	 *
	 * @param array $data {
	 *     @type array $items Items to display.
	 *     @type array $atts Shortcode attributes.
	 * }
	 * @return string HTML output.
	 */
	public function render(array $data): string
	{
		$items = isset($data['items']) ? $data['items'] : [];
		$atts = isset($data['atts']) ? $data['atts'] : [];

		if (empty($items)) {
			return '';
		}

		ob_start();

		// Get grid settings via Service
		$settings_service = SettingsService::get_instance();

		// Default values constants need to be handled.
		// We'll use hardcoded defaults if constants aren't available to avoid errors,
		// or check generic constants.
		$default_min_width = defined('DYNOS_DEFAULT_GRID_MIN_WIDTH') ? DYNOS_DEFAULT_GRID_MIN_WIDTH : '280px';
		$min_cols = defined('DYNOS_MIN_GRID_COLUMNS') ? DYNOS_MIN_GRID_COLUMNS : 1;
		$max_cols = defined('DYNOS_MAX_GRID_COLUMNS') ? DYNOS_MAX_GRID_COLUMNS : 6;

		// Allow shortcode to override min-width
		$opt_min_width = $settings_service->get_option('card_grid_min_width', $default_min_width);

		$grid_min_width = !empty($atts['min_width']) ? $atts['min_width'] : $opt_min_width;

		$grid_min_width = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_min_width, 'min-width');

		if (empty($grid_min_width)) {
			$grid_min_width = $default_min_width;
		}

		$grid_column_gap = $settings_service->get_option('card_grid_column_gap', '20px');
		$grid_row_gap = $settings_service->get_option('card_grid_row_gap', '40px');

		// Handle columns attribute
		$columns = isset($atts['columns']) ? $atts['columns'] : 'auto';
		if ('auto' !== $columns && is_numeric($columns)) {
			$columns = absint($columns);
			$columns = min(max($columns, $min_cols), $max_cols);
			$grid_style = 'grid-template-columns: repeat(' . absint($columns) . ', 1fr);';
		} else {
			$grid_style = 'grid-template-columns: repeat(auto-fit, minmax(' . $grid_min_width . ', 1fr));';
		}

		// Sanitize gaps
		$grid_column_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_column_gap, 'width');
		$grid_row_gap = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::escape_css_value($grid_row_gap, 'height');

		// Build inline style
		$grid_style_value = '';
		if (class_exists(\TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::class)) {
			$grid_style_value = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_grid_style(
				[
					'grid_style' => $grid_style,
					'column_gap' => $grid_column_gap,
					'row_gap' => $grid_row_gap,
				]
			);
		} else {
			$grid_style_value = $grid_style . ' gap: ' . $grid_row_gap . ' ' . $grid_column_gap . ';';
		}

		echo '<ul class="service-card-grid doc-service-cards" ' . (!empty($grid_style_value) ? 'style="' . esc_attr($grid_style_value) . '"' : '') . '>';

		foreach ($items as $index => $item) {
			// Output is escaped within render_fallback_card method
			echo wp_kses_post($this->render_fallback_card($item));
		}

		echo '</ul>';

		return ob_get_clean();
	}

	/**
	 * Fallback card renderer.
	 *
	 * @param array $item
	 * @return string
	 */
	private function render_fallback_card(array $item): string
	{
		$image_url = !empty($item['image_url']) ? $item['image_url'] : \TechmireSolutions\DynamicOnlineServices\Helpers\Images::get_placeholder_url();

		$settings_service = SettingsService::get_instance();

		// Pre-escaping removed for late escaping pattern

		ob_start();
		?>
		<li class="service-card type-post">
			<a href="<?php echo esc_url($item['url']); ?>" class="service-card-image-link">
				<img src="<?php echo esc_url($image_url); ?>"
					alt="<?php echo isset($item['image_alt']) ? esc_attr($item['image_alt']) : ''; ?>"
					class="service-card-image" loading="lazy">
			</a>
			<div class="service-card-content">
				<h3 class="service-card-title"><a
						href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a>
				</h3>
				<?php if (!empty($item['description'])): ?>
					<p class="service-card-description"><?php echo esc_html($item['description']); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url($item['url']); ?>"
					class="service-card-link"><?php echo esc_html($settings_service->get_option('card_button_text', 'VIEW DETAILS')); ?></a>
			</div>
		</li>
		<?php
		return ob_get_clean();
	}
}
