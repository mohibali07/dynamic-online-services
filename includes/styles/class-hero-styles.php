<?php
/**
 * Hero Section Styles
 *
 * Handles enqueuing of hero section styles and fonts.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Styles;

use TechmireSolutions\DynamicOnlineServices\Helpers\Fonts;
use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Hero Styles class.
 */
class HeroStyles
{
	/**
	 * Enqueue hero section styles and fonts.
	 *
	 * @since 1.2.0
	 * @param string $font_family    Font family name.
	 * @param string $overlay_color  Overlay color for the hero section.
	 * @return void
	 */
	public static function enqueue(string $font_family, string $overlay_color): void
	{
		// Enqueue Google Font if needed
		Fonts::enqueue_google_font($font_family, 'sos-hero-font');

		// Allow filtering overlay color
		$overlay_color = apply_filters('dynos_hero_overlay_color', $overlay_color);

		// Sanitize overlay color
		$overlay_color = sanitize_hex_color($overlay_color);
		if (empty($overlay_color)) {
			$overlay_color = '#000000';
		}

		// Enqueue hero styles
		wp_enqueue_style(
			'sos-hero-section',
			DYNOS_PLUGIN_URL . 'assets/css/hero-section.css',
			array(),
			DYNOS_VERSION
		);

		// Get options for dynamic hero settings
		$options = Options::get();
		$overlay_opacity = floatval(Options::get_option($options, 'hero_overlay_opacity', '0.5'));
		$hero_title_font_size = Options::get_option($options, 'hero_title_font_size', '3rem');
		$hero_title_font_size_tablet = Options::get_option($options, 'hero_title_font_size_tablet', '2.5rem');
		$hero_title_font_size_mobile = Options::get_option($options, 'hero_title_font_size_mobile', '2rem');
		$hero_description_font_size = Options::get_option($options, 'hero_description_font_size', '1.25rem');
		$hero_description_font_size_tablet = Options::get_option($options, 'hero_description_font_size_tablet', '1rem');
		$hero_description_font_size_mobile = Options::get_option($options, 'hero_description_font_size_mobile', '0.9rem');
		$hero_height = Options::get_option($options, 'hero_height', '50vh');
		$hero_height_tablet = Options::get_option($options, 'hero_height_tablet', '30vh');
		$hero_height_mobile = Options::get_option($options, 'hero_height_mobile', '30vh');
		$hero_content_padding = Options::get_option($options, 'hero_content_padding', '20px');
		$hero_border_radius = Options::get_option($options, 'hero_border_radius', '10px');
		$hero_margin_bottom = Options::get_option($options, 'hero_margin_bottom', '30px');

		// Sanitize and escape all CSS values to prevent injection
		$overlay_opacity = min(max($overlay_opacity, 0), 1);
		$hero_title_font_size = Sanitization::escape_css_value($hero_title_font_size, 'font-size');
		$hero_title_font_size_tablet = Sanitization::escape_css_value($hero_title_font_size_tablet, 'font-size');
		$hero_title_font_size_mobile = Sanitization::escape_css_value($hero_title_font_size_mobile, 'font-size');
		$hero_description_font_size = Sanitization::escape_css_value($hero_description_font_size, 'font-size');
		$hero_description_font_size_tablet = Sanitization::escape_css_value($hero_description_font_size_tablet, 'font-size');
		$hero_description_font_size_mobile = Sanitization::escape_css_value($hero_description_font_size_mobile, 'font-size');
		$hero_height = Sanitization::escape_css_value($hero_height, 'height');
		$hero_height_tablet = Sanitization::escape_css_value($hero_height_tablet, 'height');
		$hero_height_mobile = Sanitization::escape_css_value($hero_height_mobile, 'height');
		$hero_content_padding = Sanitization::escape_css_value($hero_content_padding, 'padding');
		$hero_border_radius = Sanitization::escape_css_value($hero_border_radius, 'border-radius');
		$hero_margin_bottom = Sanitization::escape_css_value($hero_margin_bottom, 'margin');

		// Escape breakpoint values for safe use in CSS
		$breakpoint_tablet = esc_attr(DYNOS_BREAKPOINT_TABLET);
		$breakpoint_mobile = esc_attr(DYNOS_BREAKPOINT_MOBILE);

		// Generate dynamic CSS with responsive styles using safe CSS building
		$hero_css = '.category-hero-container {' . Sanitization::build_css_rule('margin-bottom', $hero_margin_bottom) . '}';
		$hero_css .= '.category-hero-inner {' . Sanitization::build_css_rule('height', $hero_height) . '}';
		$hero_css .= '.hero-content {' . Sanitization::build_css_rule('padding', $hero_content_padding) . ' ' . Sanitization::build_css_rule('border-radius', $hero_border_radius) . '}';
		$hero_css .= '.category-hero-inner::before {' . Sanitization::build_css_rule('background-color', $overlay_color) . ' ' . Sanitization::build_css_rule('opacity', (string) $overlay_opacity) . '}';
		$hero_css .= '.category-hero-title {' . Sanitization::build_css_rule('font-size', $hero_title_font_size) . '}';
		$hero_css .= '.hero-description {' . Sanitization::build_css_rule('font-size', $hero_description_font_size) . '}';
		$hero_css .= '@media (max-width: ' . $breakpoint_tablet . ') {';
		$hero_css .= '.category-hero-inner {' . Sanitization::build_css_rule('height', $hero_height_tablet) . '}';
		$hero_css .= '.category-hero-title {' . Sanitization::build_css_rule('font-size', $hero_title_font_size_tablet) . '}';
		$hero_css .= '.hero-description {' . Sanitization::build_css_rule('font-size', $hero_description_font_size_tablet) . '}';
		$hero_css .= '}';
		$hero_css .= '@media (max-width: ' . $breakpoint_mobile . ') {';
		$hero_css .= '.category-hero-inner {' . Sanitization::build_css_rule('height', $hero_height_mobile) . '}';
		$hero_css .= '.category-hero-title {' . Sanitization::build_css_rule('font-size', $hero_title_font_size_mobile) . '}';
		$hero_css .= '.hero-description {' . Sanitization::build_css_rule('font-size', $hero_description_font_size_mobile) . '}';
		$hero_css .= '}';

		// Allow filtering the CSS
		$hero_css = apply_filters('dynos_hero_dynamic_css', $hero_css, $overlay_color, $options);

		wp_add_inline_style('sos-hero-section', $hero_css);
	}
}
