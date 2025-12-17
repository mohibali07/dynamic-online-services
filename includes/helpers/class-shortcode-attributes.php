<?php
/**
 * Shortcode Attributes Helper Class
 *
 * Handles parsing and validation of shortcode attributes.
 * Consolidates duplicate attribute parsing logic (DRY principle).
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Shortcode attributes helper class.
 */
class ShortcodeAttributes
{
	/**
	 * Parse and validate shortcode attributes with defaults.
	 *
	 * @since 1.1.0
	 * @param array  $atts Raw shortcode attributes.
	 * @param array  $defaults Default attribute values.
	 * @param string $shortcode_tag Shortcode tag name.
	 * @return array Parsed and validated attributes.
	 */
	public static function parse( array $atts, array $defaults, string $shortcode = '' ): array
	{
		// Use WordPress's built-in shortcode_atts for parsing
		$atts = \shortcode_atts( $defaults, $atts, $shortcode );

		// Sanitize all text fields
		foreach ($atts as $key => $value) {
			if (is_string($value)) {
				$atts[$key] = \sanitize_text_field($value);
			}
		}

		return $atts;
	}

	/**
	 * Validate and sanitize hero shortcode height attribute.
	 *
	 * @since 1.1.0
	 * @param string $height Height value from shortcode.
	 * @param string $default_height Default height if validation fails.
	 * @return string Validated and sanitized height value.
	 */
	public static function validate_hero_height(string $height, string $default_height): string
	{
		if (empty($height)) {
			return $default_height;
		}

		$sanitized = Sanitization::css_value($height, 'height');
		return !empty($sanitized) ? $sanitized : $default_height;
	}

	/**
	 * Validate and sanitize FAQ shortcode title attribute.
	 *
	 * @since 1.1.0
	 * @param string $title Title value from shortcode.
	 * @param string $default_title Default title if empty.
	 * @return string Validated and sanitized title value.
	 */
	public static function validate_faq_title(string $title, string $default_title): string
	{
		if (empty($title)) {
			return $default_title;
		}

		return sanitize_text_field($title);
	}
}
