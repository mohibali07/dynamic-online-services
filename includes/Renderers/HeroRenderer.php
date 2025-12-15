<?php
/**
 * Hero Renderer Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Renderers
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Renderers;

use DynamicOnlineServices\Interfaces\RendererInterface;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class HeroRenderer
 */
class HeroRenderer implements RendererInterface
{

	/**
	 * Render the hero section.
	 *
	 * @param array $data Data for rendering.
	 * @return string HTML output.
	 */
	public function render(array $data): string
	{
		$defaults = array(
			'image_url' => '',
			'height' => '50vh',
			'title_color' => '#FFFFFF',
			'font_family' => 'Helvetica',
			'title' => '',
			'description' => '',
			'data_attribute' => '',
			'data_value' => '',
			'breadcrumbs' => '',
		);

		$data = wp_parse_args($data, $defaults);

		// Sanitize all inputs
		$image_url = esc_url($data['image_url']);
		$height = $data['height'];
		$title_color = $data['title_color'];
		$font_family = $data['font_family'];
		$title = esc_html($data['title']);
		$description = !empty($data['description']) ? wp_kses_post($data['description']) : '';
		$data_attribute = sanitize_key($data['data_attribute']);
		$data_value = esc_attr($data['data_value']);

		// Build style attributes using dedicated style builder helpers
		// Note: We are still dependent on global helpers here.
		// Ideally we inject a StyleBuilder service. For this step, we keep it simple.
		$hero_inner_style_value = function_exists('dynos_build_hero_inner_style') ? dynos_build_hero_inner_style($height, $image_url) : '';
		$hero_content_style_value = function_exists('dynos_build_hero_content_style') ? dynos_build_hero_content_style($title_color, $font_family) : '';

		ob_start();
		?>
		<div class="category-hero-container" <?php if (!empty($data_attribute) && !empty($data_value)): ?> 			<?php echo esc_attr($data_attribute); ?>="<?php echo esc_attr($data_value); ?>" <?php endif; ?>>
			<div class="category-hero-inner" <?php if (!empty($hero_inner_style_value)): ?>style="<?php echo esc_attr($hero_inner_style_value); ?>" <?php endif; ?>>
				<div class="hero-content" <?php if (!empty($hero_content_style_value)): ?>style="<?php echo esc_attr($hero_content_style_value); ?>" <?php endif; ?>>
					<?php if (!empty($data['breadcrumbs'])): ?>
						<div class="dynos-breadcrumbs">
							<?php echo $data['breadcrumbs']; // Auto-escaped by Rank Math or captured HTML ?>
						</div>
					<?php endif; ?>
					<h1 class="category-hero-title"><?php echo esc_html($title); ?></h1>
					<?php if (!empty($description)): ?>
						<p class="hero-description"><?php echo wp_kses_post($description); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
