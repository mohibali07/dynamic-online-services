<?php
/**
 * Style Builder Helper Functions
 *
 * Handles building inline styles for HTML elements.
 * Separates style building logic from rendering logic (SRP).
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build inline style attribute for hero inner element.
 *
 * @since 1.1.0
 * @param string $height Hero height CSS value.
 * @param string $image_url Background image URL (optional).
 * @return string Inline style attribute value.
 */
function doc_build_hero_inner_style($height, $image_url = ''): string {
    $style_parts = array();
    
    if (!empty($height)) {
        $height_escaped = doc_escape_css_value($height, 'height');
        if (!empty($height_escaped)) {
            $style_parts[] = 'height:' . $height_escaped;
        }
    }
    
    if (!empty($image_url)) {
        $image_url_escaped = esc_url($image_url);
        if (!empty($image_url_escaped)) {
            $style_parts[] = "background-image:url('" . $image_url_escaped . "')";
        }
    }
    
    return implode('; ', $style_parts);
}

/**
 * Build inline style attribute for hero content element.
 *
 * @since 1.1.0
 * @param string $title_color Title text color.
 * @param string $font_family Font family name.
 * @return string Inline style attribute value.
 */
function doc_build_hero_content_style($title_color, $font_family): string {
    $style_parts = array();
    
    if (!empty($title_color)) {
        $color_escaped = sanitize_hex_color($title_color);
        if (!empty($color_escaped)) {
            $style_parts[] = 'color:' . $color_escaped;
        }
    }
    
    if (!empty($font_family)) {
        $font_escaped = esc_attr($font_family);
        if (!empty($font_escaped)) {
            $style_parts[] = "font-family:'" . $font_escaped . "',sans-serif";
        }
    }
    
    return implode('; ', $style_parts);
}

/**
 * Build inline style attribute for grid container.
 *
 * @since 1.1.0
 * @param array $args {
 *     Grid style arguments.
 *     @type string $grid_style Grid template columns style.
 *     @type string $column_gap Column gap CSS value.
 *     @type string $row_gap Row gap CSS value.
 * }
 * @return string Inline style attribute value.
 */
function doc_build_grid_style($args): string {
    $defaults = array(
        'grid_style' => '',
        'column_gap' => '',
        'row_gap' => '',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $style_parts = array();
    
    if (!empty($args['grid_style'])) {
        // Grid style is already built as a complete CSS rule
        $style_parts[] = $args['grid_style'];
    }
    
    if (!empty($args['column_gap'])) {
        $gap_escaped = doc_escape_css_value($args['column_gap'], 'width');
        if (!empty($gap_escaped)) {
            $style_parts[] = 'column-gap:' . $gap_escaped;
        }
    }
    
    if (!empty($args['row_gap'])) {
        $gap_escaped = doc_escape_css_value($args['row_gap'], 'height');
        if (!empty($gap_escaped)) {
            $style_parts[] = 'row-gap:' . $gap_escaped;
        }
    }
    
    return implode('; ', $style_parts);
}

