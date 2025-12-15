<?php
/**
 * Service Card Styles
 *
 * Handles enqueuing of service card styles with dynamic options.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enqueue service card styles with dynamic options.
 *
 * @since 1.1.0
 */
function dynos_enqueue_service_card_styles(): void
{
	// Check if we need to load card styles
	$load_styles = false;

	$settings = function_exists('dynos_sanitize_cpt_settings') ? dynos_sanitize_cpt_settings() : array();
	$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

	if (is_tax($taxonomy_slug)) {
		$load_styles = true;
	} else {
		// Check if current post has the shortcode
		$current_post = get_post();
		if ($current_post instanceof WP_Post && has_shortcode($current_post->post_content, 'service_category_content')) {
			$load_styles = true;
		}
	}

	// Allow filtering whether to load styles
	$post = get_post();
	$load_styles = apply_filters('dynos_should_load_card_styles', $load_styles, $post);

	if (!$load_styles) {
		return;
	}

	// Get options (cached)
	$options = \DynamicOnlineServices\Helpers\Options::get();

	$card_bg = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_bg_color', '#6A4B3F');
	$card_title = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_title_color', '#FFFFFF');
	$card_desc = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_description_color', '#e0e0e0');
	$card_btn_bg = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_button_bg_color', '#0081A7');
	$card_btn_text = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_button_text_color', '#FFFFFF');
	$card_font = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_font_family', 'Helvetica');

	// Enqueue base card styles
	wp_enqueue_style(
		'sos-service-cards',
		DYNOS_PLUGIN_URL . 'assets/css/service-cards.css',
		array(),
		DYNOS_VERSION
	);

	// Enqueue Google Font if needed
	\DynamicOnlineServices\Helpers\Fonts::enqueue_google_font($card_font, 'sos-google-font');

	// Allow filtering CSS values
	$card_bg = apply_filters('dynos_card_bg_color', $card_bg);
	$card_title = apply_filters('dynos_card_title_color', $card_title);
	$card_desc = apply_filters('dynos_card_description_color', $card_desc);
	$card_btn_bg = apply_filters('dynos_card_button_bg_color', $card_btn_bg);
	$card_btn_text = apply_filters('dynos_card_button_text_color', $card_btn_text);
	$card_font = apply_filters('dynos_card_font_family', $card_font);

	// Sanitize CSS values
	$card_bg = sanitize_hex_color($card_bg);
	$card_title = sanitize_hex_color($card_title);
	$card_desc = sanitize_hex_color($card_desc);
	$card_btn_bg = sanitize_hex_color($card_btn_bg);
	$card_btn_text = sanitize_hex_color($card_btn_text);
	$card_font = esc_attr($card_font);

	// Get additional card settings
	$card_title_size = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_title_font_size', '1.25rem');
	$card_desc_size = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_description_font_size', '0.9rem');
	$card_btn_size = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_button_font_size', '0.9rem');
	$card_border_radius = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_border_radius', '18px');
	$card_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_height', '400px');
	$card_image_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_image_height', '232px');
	$card_content_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_content_height', '200px');
	$card_content_padding_top = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_content_padding_top', '24px');
	$card_content_padding_sides = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_content_padding_sides', '10px');
	$card_grid_min_width = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_min_width', '280px');
	$card_grid_column_gap = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_column_gap', '20px');
	$card_grid_row_gap = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_grid_row_gap', '40px');
	$card_hover_transform = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_hover_transform', 'translateY(-5px)');
	$card_hover_transition = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_hover_transition', '0.2s');
	$card_box_shadow = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_box_shadow', '0 4px 6px rgba(0, 0, 0, 0.1)');
	$card_btn_hover_bg = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_button_hover_bg_color', '#FFFFFF');
	$card_btn_hover_text = \DynamicOnlineServices\Helpers\Options::get_option($options, 'card_button_hover_text_color', '#0081A7');

	// Sanitize and escape all CSS values to prevent injection
	$card_title_size = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_title_size, 'font-size');
	$card_desc_size = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_desc_size, 'font-size');
	$card_btn_size = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_btn_size, 'font-size');
	$card_border_radius = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_border_radius, 'border-radius');
	$card_height = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_height, 'height');
	$card_image_height = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_image_height, 'height');
	$card_content_height = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_content_height, 'height');
	$card_content_padding_top = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_content_padding_top, 'padding');
	$card_content_padding_sides = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_content_padding_sides, 'padding');
	$card_grid_min_width = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_grid_min_width, 'min-width');
	$card_grid_column_gap = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_grid_column_gap, 'padding');
	$card_grid_row_gap = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_grid_row_gap, 'padding');
	$card_hover_transform = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_hover_transform, 'transform');
	$card_hover_transition = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_hover_transition, '');
	$card_box_shadow = \DynamicOnlineServices\Helpers\Sanitization::escape_css_value($card_box_shadow, 'box-shadow');
	$card_btn_hover_bg = sanitize_hex_color($card_btn_hover_bg);
	$card_btn_hover_text = sanitize_hex_color($card_btn_hover_text);

	// Escape font family for safe use in CSS
	$card_font_escaped = esc_attr($card_font);

	// Build dynamic CSS using safe CSS building functions
	// These styles override base CSS file with user-configured values
	// Allows real-time customization without editing CSS files
	$dynamic_css = '.service-card-grid {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('grid-template-columns', 'repeat(auto-fit, minmax(' . $card_grid_min_width . ', 1fr))');
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('column-gap', $card_grid_column_gap);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('row-gap', $card_grid_row_gap);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('border-radius', $card_border_radius);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('height', $card_height);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('box-shadow', $card_box_shadow);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('transition', 'transform ' . $card_hover_transition);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card:hover {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('transform', $card_hover_transform);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-image-link {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('height', $card_image_height);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('border-radius', $card_border_radius);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-image {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('height', $card_image_height);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('border-radius', $card_border_radius);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-content {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('background-color', $card_bg);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_title);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('font-family', $card_font_escaped . ', sans-serif');
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('padding', $card_content_padding_top . ' ' . $card_content_padding_sides . ' 0');
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('height', $card_content_height);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('border-radius', '0 0 ' . $card_border_radius . ' ' . $card_border_radius);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-title, .service-card-title a {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_title);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('font-size', $card_title_size);
	$dynamic_css .= '}';

	// Allow filtering hover color (defaults to white for contrast)
	$card_title_hover_color = apply_filters('dynos_card_title_hover_color', '#ffffff', $options);
	$card_title_hover_color = sanitize_hex_color($card_title_hover_color);
	if (empty($card_title_hover_color)) {
		$card_title_hover_color = '#ffffff';
	}

	$dynamic_css .= '.service-card-title a:hover {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_title_hover_color);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-description {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_desc);
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('font-size', $card_desc_size);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-link {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('background-color', $card_btn_bg);
	// Use inline style approach for !important (safer than string concatenation)
	$color_rule = \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_btn_text);
	if (!empty($color_rule)) {
		$dynamic_css .= rtrim($color_rule, ';') . ' !important;';
	}
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('font-size', $card_btn_size);
	$dynamic_css .= '}';

	$dynamic_css .= '.service-card-link:hover {';
	$dynamic_css .= \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('background-color', $card_btn_hover_bg);
	// Use inline style approach for !important (safer than string concatenation)
	$hover_color_rule = \DynamicOnlineServices\Helpers\Sanitization::build_css_rule('color', $card_btn_hover_text);
	if (!empty($hover_color_rule)) {
		$dynamic_css .= rtrim($hover_color_rule, ';') . ' !important;';
	}
	$dynamic_css .= '}';

	// Allow filtering the CSS before adding
	$dynamic_css = apply_filters('dynos_card_dynamic_css', $dynamic_css, $options);

	wp_add_inline_style('sos-service-cards', $dynamic_css);
}
add_action('wp_enqueue_scripts', 'dynos_enqueue_service_card_styles');
