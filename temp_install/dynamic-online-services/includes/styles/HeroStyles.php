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

		// Enqueue hero styles - Using the public (modern) CSS file
		wp_enqueue_style(
			'sos-hero-section',
			DYNOS_PLUGIN_URL . 'assets/public/css/hero-section.css',
			array(),
			DYNOS_VERSION
		);

		// Get options for dynamic hero settings
		$options = Options::get();

		// Retrieve and normalize options
		$defaults = array(
			'hero_overlay_opacity'              => '0.5',
			'hero_title_font_size'              => '3rem',
			'hero_title_font_size_tablet'       => '2.5rem',
			'hero_title_font_size_mobile'       => '2rem',
			'hero_description_font_size'        => '1.25rem',
			'hero_description_font_size_tablet' => '1rem',
			'hero_description_font_size_mobile' => '0.9rem',
			'hero_height'                       => '50vh',
			'hero_height_tablet'                => '30vh',
			'hero_height_mobile'                => '30vh',
			'hero_content_padding'              => '20px',
			'hero_border_radius'                => '10px',
			'hero_margin_bottom'                => '30px',
		);

		$settings = array();
		foreach ($defaults as $key => $default) {
			$settings[$key] = Options::get_option($options, $key, $default);
		}

		// Sanitize values
		$overlay_opacity = min(max(floatval($settings['hero_overlay_opacity']), 0), 1);

		// Build CSS Variables mapping
		$css_vars = array(
			'--hero-overlay-color'                  => $overlay_color,
			'--hero-overlay-opacity'                => (string) $overlay_opacity,
			'--hero-title-font-size'                => Sanitization::escape_css_value($settings['hero_title_font_size'], 'font-size'),
			'--hero-title-font-size-tablet'         => Sanitization::escape_css_value($settings['hero_title_font_size_tablet'], 'font-size'),
			'--hero-title-font-size-mobile'         => Sanitization::escape_css_value($settings['hero_title_font_size_mobile'], 'font-size'),
			'--hero-description-font_size'          => Sanitization::escape_css_value($settings['hero_description_font_size'], 'font-size'),
			'--hero-description-font-size-tablet'   => Sanitization::escape_css_value($settings['hero_description_font_size_tablet'], 'font-size'),
			'--hero-description-font-size-mobile'   => Sanitization::escape_css_value($settings['hero_description_font_size_mobile'], 'font-size'),
			'--hero-height'                         => Sanitization::escape_css_value($settings['hero_height'], 'height'),
			'--hero-height-tablet'                  => Sanitization::escape_css_value($settings['hero_height_tablet'], 'height'),
			'--hero-height-mobile'                  => Sanitization::escape_css_value($settings['hero_height_mobile'], 'height'),
			'--hero-content-padding'                => Sanitization::escape_css_value($settings['hero_content_padding'], 'padding'),
			'--hero-border-radius'                  => Sanitization::escape_css_value($settings['hero_border_radius'], 'border-radius'),
			'--hero-margin-bottom'                  => Sanitization::escape_css_value($settings['hero_margin_bottom'], 'margin'),
		);

		// Generate CSS Variables block
		// We verify the style handle is valid before adding inline style, though wp_enqueue_style was just called.
		$css_string = ":root {\n";
		foreach ($css_vars as $var => $value) {
			$css_string .= "\t{$var}: {$value};\n";
		}
		$css_string .= "}\n";

		// Allow filtering the CSS variables
		$css_string = apply_filters('dynos_hero_css_variables', $css_string, $css_vars);

		wp_add_inline_style('sos-hero-section', $css_string);
	}
}
