<?php
/**
 * Sanitization Helper Functions
 *
 * Handles additional sanitization functions for CSS and other data types.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize CSS dimension value (px, rem, em, %, vh, vw).
 *
 * @since 1.1.0
 * @param string $value CSS dimension value.
 * @return string Sanitized CSS dimension value.
 */
function doc_sanitize_css_dimension($value): string {
    if (empty($value)) {
        return '';
    }

    // Allow alphanumeric, spaces, dots, hyphens, and common CSS units
    $sanitized = preg_replace('/[^a-zA-Z0-9\s\.\-\%pxrememvhvw]/', '', $value);
    
    // Validate format: number followed by unit
    if (!preg_match('/^[\d\.]+(px|rem|em|%|vh|vw|auto)$/i', trim($sanitized)) && trim($sanitized) !== 'auto') {
        // If doesn't match standard format, try to extract valid parts
        $sanitized = preg_replace('/[^a-zA-Z0-9\s\.\-\%pxrememvhvw]/', '', $value);
    }

    return trim($sanitized);
}

/**
 * Sanitize CSS transform value.
 *
 * @since 1.1.0
 * @param string $value CSS transform value.
 * @return string Sanitized CSS transform value.
 */
function doc_sanitize_css_transform($value): string {
    if (empty($value)) {
        return '';
    }

    // Allow common CSS transform functions and their values
    // Whitelist approach: only allow known safe transform functions
    $allowed_functions = array('translate', 'translateX', 'translateY', 'translateZ', 'scale', 'scaleX', 'scaleY', 'rotate', 'skew', 'skewX', 'skewY', 'matrix', 'matrix3d', 'perspective');
    $pattern = '/\b(' . implode('|', $allowed_functions) . ')\s*\([^)]*\)/i';
    
    // Extract only valid transform functions from the input
    preg_match_all($pattern, $value, $matches);
    
    if (!empty($matches[0])) {
        // Reconstruct from matched valid transforms
        // This ensures only whitelisted functions are included
        return implode(' ', $matches[0]);
    }

    // Fallback: strip dangerous characters but keep safe ones
    // Used when no valid transform functions are found
    return preg_replace('/[^a-zA-Z0-9\s\(\)\.\-\%px,]/', '', $value);
}

/**
 * Sanitize CSS box-shadow value.
 *
 * @since 1.1.0
 * @param string $value CSS box-shadow value.
 * @return string Sanitized CSS box-shadow value.
 */
function doc_sanitize_css_box_shadow($value): string {
    if (empty($value) || 'none' === strtolower(trim($value))) {
        return '';
    }

    // Allow common box-shadow format: offset-x offset-y blur spread color
    // Strip potentially dangerous characters but keep safe CSS syntax
    $sanitized = preg_replace('/[^a-zA-Z0-9\s\(\)\.\-\%pxrememrgba,]/', '', $value);
    
    return trim($sanitized);
}

/**
 * Sanitize CSS value for inline styles (general purpose).
 *
 * @since 1.1.0
 * @param string $value CSS value.
 * @param string $property CSS property name (optional, for context-specific sanitization).
 * @return string Sanitized CSS value.
 */
function doc_sanitize_css_value($value, $property = ''): string {
    if (empty($value)) {
        return '';
    }

    // Property-specific sanitization
    switch (strtolower($property)) {
        case 'transform':
            return doc_sanitize_css_transform($value);
        case 'box-shadow':
            return doc_sanitize_css_box_shadow($value);
        case 'height':
        case 'width':
        case 'max-height':
        case 'min-width':
        case 'font-size':
        case 'padding':
        case 'margin':
        case 'border-radius':
            return doc_sanitize_css_dimension($value);
        default:
            // General sanitization: remove potentially dangerous characters
            return wp_strip_all_tags($value);
    }
}

/**
 * Validate post object.
 *
 * @since 1.1.0
 * @param mixed $post Post object or post ID.
 * @param string $post_type Optional post type to verify. Default empty.
 * @return WP_Post|false Valid post object or false on failure.
 */
function doc_validate_post_object($post, $post_type = ''): WP_Post|false {
    if (empty($post)) {
        return false;
    }
    
    // If it's a post ID, get the post object
    if (is_numeric($post)) {
        $post_id = absint($post);
        if (0 === $post_id) {
            return false;
        }
        $post = get_post($post_id);
    }
    
    // Validate post object
    if (!is_object($post) || !isset($post->ID)) {
        return false;
    }
    
    // Verify post exists
    if (!get_post($post->ID)) {
        return false;
    }
    
    // Verify post type if specified
    if (!empty($post_type) && get_post_type($post) !== $post_type) {
        return false;
    }
    
    return $post;
}

/**
 * Validate and sanitize post ID.
 *
 * @since 1.1.0
 * @param mixed $post_id Post ID to validate.
 * @return int|false Valid post ID or false on failure.
 */
function doc_validate_post_id($post_id): int|false {
    if (empty($post_id)) {
        return false;
    }
    
    $post_id = absint($post_id);
    if (0 === $post_id) {
        return false;
    }
    
    // Verify post exists
    if (!get_post($post_id)) {
        return false;
    }
    
    return $post_id;
}

/**
 * Validate term object.
 *
 * @since 1.1.0
 * @param mixed $term Term object or term ID.
 * @param string $taxonomy Optional taxonomy slug to verify. Default empty.
 * @return WP_Term|false Valid term object or false on failure.
 */
function doc_validate_term_object($term, $taxonomy = ''): WP_Term|false {
    if (empty($term)) {
        return false;
    }
    
    // If it's a term ID, get the term object
    if (is_numeric($term)) {
        $term_id = absint($term);
        if (0 === $term_id) {
            return false;
        }
        $term = get_term($term_id, $taxonomy);
    }
    
    // Validate term object
    if (!is_object($term) || is_wp_error($term) || !isset($term->term_id)) {
        return false;
    }
    
    // Verify term ID is valid
    if (0 === absint($term->term_id)) {
        return false;
    }
    
    // Verify taxonomy if specified
    if (!empty($taxonomy) && isset($term->taxonomy) && $term->taxonomy !== $taxonomy) {
        return false;
    }
    
    return $term;
}

/**
 * Validate and sanitize term ID.
 *
 * @since 1.1.0
 * @param mixed $term_id Term ID to validate.
 * @param string $taxonomy Taxonomy slug (optional, for verification).
 * @return int|false Valid term ID or false on failure.
 */
function doc_validate_term_id($term_id, $taxonomy = ''): int|false {
    if (empty($term_id)) {
        return false;
    }
    
    $term_id = absint($term_id);
    if (0 === $term_id) {
        return false;
    }
    
    // Verify term exists if taxonomy provided
    if (!empty($taxonomy)) {
        $term = get_term($term_id, $taxonomy);
        if (is_wp_error($term) || !$term) {
            return false;
        }
    }
    
    return $term_id;
}

/**
 * Validate and sanitize attachment ID.
 *
 * @since 1.1.0
 * @param mixed $attachment_id Attachment ID to validate.
 * @return int|false Valid attachment ID or false on failure.
 */
function doc_validate_attachment_id($attachment_id): int|false {
    if (empty($attachment_id)) {
        return false;
    }
    
    $attachment_id = absint($attachment_id);
    if (0 === $attachment_id) {
        return false;
    }
    
    // Verify attachment exists and is an image
    $attachment = get_post($attachment_id);
    if (!$attachment || 'attachment' !== $attachment->post_type || !wp_attachment_is_image($attachment_id)) {
        return false;
    }
    
    return $attachment_id;
}

/**
 * Validate slug value.
 *
 * @since 1.1.0
 * @param mixed $slug Slug value to validate.
 * @param string $default Default slug if validation fails.
 * @return string Validated slug.
 */
function doc_validate_slug($slug, $default = ''): string {
    if (empty($slug)) {
        return $default;
    }
    
    $sanitized = sanitize_title($slug);
    return empty($sanitized) ? $default : $sanitized;
}

/**
 * Validate numeric range value.
 *
 * @since 1.1.0
 * @param mixed $value Value to validate.
 * @param int $min Minimum allowed value.
 * @param int $max Maximum allowed value.
 * @param mixed $default Default value if validation fails.
 * @return mixed Validated value within range or default.
 */
function doc_validate_numeric_range($value, $min, $max, $default = null): mixed {
    if (is_null($value) || '' === $value) {
        return $default;
    }
    
    $value = absint($value);
    if ($value < $min || $value > $max) {
        return $default;
    }
    
    return $value;
}

/**
 * Safely escape CSS value for use in inline styles.
 * Prevents CSS injection by ensuring values are properly formatted.
 *
 * @since 1.1.0
 * @param string $value CSS value to escape.
 * @param string $property CSS property name for context-specific validation.
 * @return string Escaped CSS value safe for inline style output. Empty string if value is invalid or dangerous.
 */
function doc_escape_css_value($value, $property = ''): string {
    if (empty($value) && '0' !== $value) {
        return '';
    }
    
    // First sanitize using existing function
    $sanitized = doc_sanitize_css_value($value, $property);
    
    // Additional escaping: remove any remaining dangerous characters
    // Remove null bytes, control characters, and potential injection vectors
    $sanitized = str_replace(array("\0", "\r", "\n", "\t"), '', $sanitized);
    
    // Remove potential CSS injection patterns
    // Block expressions, javascript:, url(javascript:), etc.
    $dangerous_patterns = array(
        '/expression\s*\(/i',
        '/javascript\s*:/i',
        '/@import/i',
        '/url\s*\(\s*["\']?\s*javascript:/i',
        '/<script/i',
        '/<\/script>/i',
    );
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $sanitized)) {
            // If dangerous pattern found, return empty string
            return '';
        }
    }
    
    return $sanitized;
}

/**
 * Safely build CSS rule with property and value.
 * Ensures both property and value are safe before combining.
 *
 * @since 1.1.0
 * @param string $property CSS property name.
 * @param string $value CSS value.
 * @return string Safe CSS rule (property: value;) or empty string if invalid.
 */
function doc_build_css_rule($property, $value): string {
    if (empty($property) || empty($value)) {
        return '';
    }
    
    // Sanitize property name (alphanumeric, hyphens, underscores only)
    $property = preg_replace('/[^a-zA-Z0-9\-_]/', '', $property);
    if (empty($property)) {
        return '';
    }
    
    // Escape the value
    $escaped_value = doc_escape_css_value($value, $property);
    if (empty($escaped_value)) {
        return '';
    }
    
    return $property . ': ' . $escaped_value . ';';
}

