<?php
/**
 * Hero Section Shortcodes
 *
 * Main loader for hero section shortcode functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include hero shortcode components
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/renderer.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/data.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/shortcode-handler.php';

/**
 * Register the hero section shortcode for category archive pages.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return string HTML output or empty string.
 */
function doc_display_service_category_hero_shortcode($atts = array()): string
{
    return \DynamicOnlineServices\Shortcodes\Hero::render_category_hero($atts);
}
add_shortcode('service_category_hero', 'doc_display_service_category_hero_shortcode');

/**
 * Register the hero section shortcode for single service pages.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return string HTML output or empty string.
 */
function doc_display_single_service_hero_shortcode($atts = array()): string
{
    return \DynamicOnlineServices\Shortcodes\Hero::render_service_hero($atts);
}
add_shortcode('single_service_hero', 'doc_display_single_service_hero_shortcode');

