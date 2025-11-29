<?php
/**
 * Shortcode Attributes Helper Functions
 *
 * Handles parsing and validation of shortcode attributes.
 * Consolidates duplicate attribute parsing logic (DRY principle).
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Parse and validate shortcode attributes with defaults.
 *
 * @since 1.1.0
 * @param array  $atts Raw shortcode attributes.
 * @param array  $defaults Default attribute values.
 * @param string $shortcode_tag Shortcode tag name.
 * @return array Parsed and validated attributes.
 */
function doc_parse_shortcode_attributes($atts, $defaults, $shortcode_tag): array {
    // Use WordPress's built-in shortcode_atts for parsing
    $parsed = shortcode_atts($defaults, $atts, $shortcode_tag);
    
    // Sanitize all text fields
    foreach ($parsed as $key => $value) {
        if (is_string($value)) {
            $parsed[$key] = sanitize_text_field($value);
        }
    }
    
    return $parsed;
}

/**
 * Validate and sanitize hero shortcode height attribute.
 *
 * @since 1.1.0
 * @param string $height Height value from shortcode.
 * @param string $default_height Default height if validation fails.
 * @return string Validated and sanitized height value.
 */
function doc_validate_hero_height_attribute($height, $default_height): string {
    if (empty($height)) {
        return $default_height;
    }
    
    $sanitized = doc_sanitize_css_value($height, 'height');
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
function doc_validate_faq_title_attribute($title, $default_title): string {
    if (empty($title)) {
        return $default_title;
    }
    
    return sanitize_text_field($title);
}

