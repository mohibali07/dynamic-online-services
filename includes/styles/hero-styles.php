<?php
/**
 * Hero Section Styles
 *
 * Handles enqueuing of hero section styles and fonts.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue hero section styles and fonts.
 *
 * @since 1.1.0
 * @param string $font_family    Font family name.
 * @param string $overlay_color  Overlay color for the hero section.
 * @return void
 */
function doc_enqueue_hero_styles($font_family, $overlay_color): void {
    // Enqueue Google Font if needed
    doc_enqueue_google_font($font_family, 'sos-hero-font');

    // Allow filtering overlay color
    $overlay_color = apply_filters('doc_hero_overlay_color', $overlay_color);

    // Sanitize overlay color
    $overlay_color = sanitize_hex_color($overlay_color);
    if (empty($overlay_color)) {
        $overlay_color = '#000000';
    }

    // Enqueue hero styles
    wp_enqueue_style(
        'sos-hero-section',
        DOC_PLUGIN_URL . 'assets/css/hero-section.css',
        array(),
        DOC_VERSION
    );

    // Get options for dynamic hero settings
    $options = doc_get_options();
    $overlay_opacity = floatval(doc_get_option($options, 'hero_overlay_opacity', '0.5'));
    $hero_title_font_size = doc_get_option($options, 'hero_title_font_size', '3rem');
    $hero_title_font_size_tablet = doc_get_option($options, 'hero_title_font_size_tablet', '2.5rem');
    $hero_title_font_size_mobile = doc_get_option($options, 'hero_title_font_size_mobile', '2rem');
    $hero_description_font_size = doc_get_option($options, 'hero_description_font_size', '1.25rem');
    $hero_description_font_size_tablet = doc_get_option($options, 'hero_description_font_size_tablet', '1rem');
    $hero_description_font_size_mobile = doc_get_option($options, 'hero_description_font_size_mobile', '0.9rem');
    $hero_height = doc_get_option($options, 'hero_height', '50vh');
    $hero_height_tablet = doc_get_option($options, 'hero_height_tablet', '30vh');
    $hero_height_mobile = doc_get_option($options, 'hero_height_mobile', '30vh');
    $hero_content_padding = doc_get_option($options, 'hero_content_padding', '20px');
    $hero_border_radius = doc_get_option($options, 'hero_border_radius', '10px');
    $hero_margin_bottom = doc_get_option($options, 'hero_margin_bottom', '30px');

    // Sanitize and escape all CSS values to prevent injection
    $overlay_opacity = min(max($overlay_opacity, 0), 1);
    $hero_title_font_size = doc_escape_css_value($hero_title_font_size, 'font-size');
    $hero_title_font_size_tablet = doc_escape_css_value($hero_title_font_size_tablet, 'font-size');
    $hero_title_font_size_mobile = doc_escape_css_value($hero_title_font_size_mobile, 'font-size');
    $hero_description_font_size = doc_escape_css_value($hero_description_font_size, 'font-size');
    $hero_description_font_size_tablet = doc_escape_css_value($hero_description_font_size_tablet, 'font-size');
    $hero_description_font_size_mobile = doc_escape_css_value($hero_description_font_size_mobile, 'font-size');
    $hero_height = doc_escape_css_value($hero_height, 'height');
    $hero_height_tablet = doc_escape_css_value($hero_height_tablet, 'height');
    $hero_height_mobile = doc_escape_css_value($hero_height_mobile, 'height');
    $hero_content_padding = doc_escape_css_value($hero_content_padding, 'padding');
    $hero_border_radius = doc_escape_css_value($hero_border_radius, 'border-radius');
    $hero_margin_bottom = doc_escape_css_value($hero_margin_bottom, 'margin');
    
    // Escape breakpoint values for safe use in CSS
    $breakpoint_tablet = esc_attr(DOC_BREAKPOINT_TABLET);
    $breakpoint_mobile = esc_attr(DOC_BREAKPOINT_MOBILE);

    // Generate dynamic CSS with responsive styles using safe CSS building
    // This CSS is injected inline to allow per-page customization
    // Responsive breakpoints use constants defined in main plugin file
    $hero_css = '.category-hero-container {' . doc_build_css_rule('margin-bottom', $hero_margin_bottom) . '}';
    $hero_css .= '.category-hero-inner {' . doc_build_css_rule('height', $hero_height) . '}';
    $hero_css .= '.hero-content {' . doc_build_css_rule('padding', $hero_content_padding) . ' ' . doc_build_css_rule('border-radius', $hero_border_radius) . '}';
    $hero_css .= '.category-hero-inner::before {' . doc_build_css_rule('background-color', $overlay_color) . ' ' . doc_build_css_rule('opacity', (string) $overlay_opacity) . '}';
    $hero_css .= '.category-hero-title {' . doc_build_css_rule('font-size', $hero_title_font_size) . '}';
    $hero_css .= '.hero-description {' . doc_build_css_rule('font-size', $hero_description_font_size) . '}';
    $hero_css .= '@media (max-width: ' . $breakpoint_tablet . ') {';
    $hero_css .= '.category-hero-inner {' . doc_build_css_rule('height', $hero_height_tablet) . '}';
    $hero_css .= '.category-hero-title {' . doc_build_css_rule('font-size', $hero_title_font_size_tablet) . '}';
    $hero_css .= '.hero-description {' . doc_build_css_rule('font-size', $hero_description_font_size_tablet) . '}';
    $hero_css .= '}';
    $hero_css .= '@media (max-width: ' . $breakpoint_mobile . ') {';
    $hero_css .= '.category-hero-inner {' . doc_build_css_rule('height', $hero_height_mobile) . '}';
    $hero_css .= '.category-hero-title {' . doc_build_css_rule('font-size', $hero_title_font_size_mobile) . '}';
    $hero_css .= '.hero-description {' . doc_build_css_rule('font-size', $hero_description_font_size_mobile) . '}';
    $hero_css .= '}';

    // Allow filtering the CSS
    $hero_css = apply_filters('doc_hero_dynamic_css', $hero_css, $overlay_color, $options);

    wp_add_inline_style('sos-hero-section', $hero_css);
}

