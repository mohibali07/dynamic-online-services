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

namespace TechmireSolutions\DynamicOnlineServices\Styles;

use TechmireSolutions\DynamicOnlineServices\Helpers\Fonts;
use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;
use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization as CPTSanitization;
use WP_Post;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Card Styles class.
 */
class CardStyles
{
	/**
	 * Initialize hooks.
	 *
	 * @since 1.2.0
	 */
	public static function init(): void
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue'));
	}

	/**
	 * Enqueue service card styles with dynamic options.
	 *
	 * @since 1.2.0
	 */
	public static function enqueue(bool $force = false): void
	{
		// Check if we need to load card styles
		$load_styles = $force;

		$settings = CPTSanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

		if (!$load_styles) {
			if (is_tax($taxonomy_slug)) {
				$load_styles = true;
			} else {
				// Check if current post has the shortcode
				$current_post = get_post();
				if ($current_post instanceof WP_Post && has_shortcode($current_post->post_content, 'service_category_content')) {
					$load_styles = true;
				}
			}
		}

		// Allow filtering whether to load styles
		$post = get_post();
		$load_styles = apply_filters('dynos_should_load_card_styles', $load_styles, $post);

		if (!$load_styles) {
			return;
		}

		// Get options (cached)
		$options = Options::get();

		// Defaults
		$defaults = array(
			'card_bg_color'               => '#6A4B3F',
			'card_title_color'            => '#FFFFFF',
			'card_description_color'      => '#e0e0e0',
			'card_button_bg_color'        => '#0081A7',
			'card_button_text_color'      => '#FFFFFF',
			'card_button_hover_bg_color'   => '#FFFFFF',
			'card_button_hover_text_color' => '#0081A7',
			'card_font_family'            => 'Helvetica',
			'card_title_font_size'        => '1.25rem',
			'card_description_font_size'  => '0.9rem',
			'card_button_font_size'       => '0.9rem',
			'card_border_radius'          => '18px',
			'card_height'                 => '400px',
			'card_image_height'           => '232px',
			'card_content_height'         => '200px',
			'card_content_padding_top'    => '24px',
			'card_content_padding_sides'  => '10px',
			'card_grid_min_width'         => '280px',
			'card_grid_column_gap'        => '20px',
			'card_grid_row_gap'           => '40px',
			'card_hover_transform'        => 'translateY(-5px)',
			'card_hover_transition'       => '0.2s',
			'card_box_shadow'             => '0 4px 6px rgba(0, 0, 0, 0.1)',
		);

		// Get current settings merging with defaults
		$settings = array();
		foreach ($defaults as $key => $default) {
			$settings[ $key ] = Options::get_option($options, $key, $default);
		}

		// Enqueue base card styles
		// Enqueue base card styles
		wp_enqueue_style(
			'sos-service-cards',
			DYNOS_PLUGIN_URL . 'assets/public/css/service-cards.css',
			array(),
			DYNOS_VERSION
		);

		// Enqueue Google Font if needed
		Fonts::enqueue_google_font($settings['card_font_family'], 'sos-google-font');

		// Sanitize Colors
		$colors = array(
			'card_bg_color', 'card_title_color', 'card_description_color',
			'card_button_bg_color', 'card_button_text_color',
			'card_button_hover_bg_color', 'card_button_hover_text_color'
		);
		foreach ($colors as $color_key) {
			$settings[$color_key] = sanitize_hex_color($settings[$color_key]);
		}

		// Calculate Hover Title Color
		$card_title_hover_color = apply_filters('dynos_card_title_hover_color', '#ffffff', $options);
		$settings['card_title_hover_color'] = sanitize_hex_color($card_title_hover_color);

		// Build CSS Variables Map
		$css_vars = array(
			'--card-bg'                     => $settings['card_bg_color'],
			'--card-title-color'            => $settings['card_title_color'],
			'--card-title-hover-color'      => $settings['card_title_hover_color'],
			'--card-description-color'      => $settings['card_description_color'],
			'--card-btn-bg'                 => $settings['card_button_bg_color'],
			'--card-btn-text'               => $settings['card_button_text_color'],
			'--card-btn-hover-bg'           => $settings['card_button_hover_bg_color'],
			'--card-btn-hover-text'         => $settings['card_button_hover_text_color'],
			'--card-font-family'            => esc_attr($settings['card_font_family']),
			'--card-title-size'             => Sanitization::escape_css_value($settings['card_title_font_size'], 'font-size'),
			'--card-desc-size'              => Sanitization::escape_css_value($settings['card_description_font_size'], 'font-size'),
			'--card-btn-size'               => Sanitization::escape_css_value($settings['card_button_font_size'], 'font-size'),
			'--card-radius'                 => Sanitization::escape_css_value($settings['card_border_radius'], 'border-radius'),
			'--card-height'                 => Sanitization::escape_css_value($settings['card_height'], 'height'),
			'--card-img-height'             => Sanitization::escape_css_value($settings['card_image_height'], 'height'),
			'--card-content-height'         => Sanitization::escape_css_value($settings['card_content_height'], 'height'),
			'--card-padding-top'            => Sanitization::escape_css_value($settings['card_content_padding_top'], 'padding'),
			'--card-padding-sides'          => Sanitization::escape_css_value($settings['card_content_padding_sides'], 'padding'),
			'--card-grid-min-width'         => Sanitization::escape_css_value($settings['card_grid_min_width'], 'min-width'),
			'--card-col-gap'                => Sanitization::escape_css_value($settings['card_grid_column_gap'], 'padding'),
			'--card-row-gap'                => Sanitization::escape_css_value($settings['card_grid_row_gap'], 'padding'),
			'--card-hover-transform'        => Sanitization::escape_css_value($settings['card_hover_transform'], 'transform'),
			'--card-hover-transition'       => Sanitization::escape_css_value($settings['card_hover_transition'], ''),
			'--card-shadow'                 => Sanitization::escape_css_value($settings['card_box_shadow'], 'box-shadow'),
		);

		// Generate CSS Variables block
		$css_string = ":root {\n";
		foreach ($css_vars as $var => $value) {
			if (!empty($value)) {
				$css_string .= "\t{$var}: {$value};\n";
			}
		}
		$css_string .= "}\n";

		// Allow filtering the CSS variables
		$css_string = apply_filters('dynos_card_css_variables', $css_string, $css_vars);

		wp_add_inline_style('sos-service-cards', $css_string);
	}
}
