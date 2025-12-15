<?php
/**
 * Hero Shortcode Handler
 *
 * Shared handler for hero shortcode functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Common hero shortcode processing logic.
 *
 * @since 1.1.0
 * @param array  $atts          Shortcode attributes.
 * @param array  $hero_data     Hero data array from data retrieval functions.
 * @param string $shortcode_tag Shortcode tag name.
 * @return string HTML output or empty string.
 */
function dynos_process_hero_shortcode($atts, $hero_data, $shortcode_tag): string
{
	// Get default height from settings
	$options = \DynamicOnlineServices\Helpers\Options::get();
	$default_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_height', '50vh');

	// Parse shortcode attributes using consolidated helper
	$atts = dynos_parse_shortcode_attributes(
		$atts,
		array('height' => $default_height),
		$shortcode_tag
	);

	// Validate and sanitize height attribute using dedicated helper
	$atts['height'] = dynos_validate_hero_height_attribute($atts['height'], $default_height);

	// Override height if provided in shortcode
	if (!empty($atts['height'])) {
		$hero_data['height'] = $atts['height'];
	}

	// Enqueue hero styles and fonts
	dynos_enqueue_hero_styles($hero_data['font_family'], $hero_data['overlay_color']);

	// Render hero HTML
	$output = dynos_render_hero_html($hero_data);

	return $output;
}
