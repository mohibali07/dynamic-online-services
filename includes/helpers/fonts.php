<?php
/**
 * Font Helper Functions
 *
 * Handles font-related functionality including Google Fonts.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Fonts
 *
 * Replaces global functions for font handling.
 */
class Fonts
{
    /**
     * Standard font families that don't need Google Fonts loading.
     *
     * @return array Standard fonts list.
     */
    public static function get_standard_fonts(): array
    {
        if (!defined('DYNOS_STANDARD_FONTS')) {
            define('DYNOS_STANDARD_FONTS', array('sans-serif', 'serif', 'monospace', 'Helvetica'));
        }
        return DYNOS_STANDARD_FONTS;
    }

    /**
     * Check if a font family requires Google Fonts loading.
     *
     * @since 1.1.0
     * @param string $font_family Font family name. Must be a non-empty string.
     * @return bool True if font requires Google Fonts, false otherwise. Returns false if font_family is empty or is a standard font.
     */
    public static function is_google_font($font_family): bool
    {
        if (empty($font_family) || !is_string($font_family)) {
            return false;
        }
        return !in_array($font_family, self::get_standard_fonts(), true);
    }

    /**
     * Encode font family name for Google Fonts URL.
     *
     * @since 1.1.0
     * @param string $font_family Font family name. Must be a valid string.
     * @return string Encoded font family name safe for use in URLs. Returns empty string if input is invalid.
     */
    public static function encode_google_font($font_family): string
    {
        if (empty($font_family) || !is_string($font_family)) {
            return '';
        }

        // Sanitize and encode font family name for Google Fonts URL
        $font_family = sanitize_text_field($font_family);
        if (empty($font_family)) {
            return '';
        }

        // Replace spaces with + for Google Fonts API format
        // Google Fonts API expects spaces as + signs in URLs
        $encoded = str_replace(' ', '+', $font_family);
        // Use rawurlencode for better RFC 3986 compliance
        return rawurlencode($encoded);
    }

    /**
     * Enqueue Google Font if needed.
     *
     * @since 1.1.0
     * @param string $font_family Font family name. Must be a valid Google Font name.
     * @param string $handle      Style handle for the font. Default 'sos-google-font'. Must be a valid string.
     * @return bool True if font was enqueued successfully, false otherwise (e.g., if it's a standard font or encoding fails).
     */
    public static function enqueue_google_font($font_family, $handle = 'sos-google-font'): bool
    {
        if (empty($font_family) || !is_string($font_family)) {
            return false;
        }

        if (empty($handle) || !is_string($handle)) {
            $handle = 'sos-google-font';
        }

        if (!self::is_google_font($font_family)) {
            return false;
        }

        // Allow filtering font family
        $font_family = apply_filters('dynos_google_font_family', $font_family, $handle);

        if (empty($font_family)) {
            return false;
        }

        $font_family_encoded = self::encode_google_font($font_family);

        // Validate encoded font name
        if (empty($font_family_encoded)) {
            return false;
        }

        // Build Google Fonts API URL
        // Using CSS2 API with weights 400 (normal) and 700 (bold)
        // display=swap ensures text is visible during font load (FOUT instead of FOIT)
        $font_url = apply_filters(
            'dynos_google_font_url',
            'https://fonts.googleapis.com/css2?family=' . $font_family_encoded . ':wght@400;700&display=swap',
            $font_family,
            $font_family_encoded
        );

        // Validate URL before enqueuing
        if (empty($font_url) || !filter_var($font_url, FILTER_VALIDATE_URL)) {
            return false;
        }

        wp_enqueue_style(
            $handle,
            esc_url($font_url),
            array(),
            DYNOS_VERSION
        );

        return true;
    }
}
