<?php
/**
 * Category Content Shortcode
 *
 * Main shortcode handler for service category content.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include category shortcode components
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/validation.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/query.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/renderer.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/pagination.php';

/**
 * Shortcode to display service category content (child categories and services).
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function doc_display_service_category_content_shortcode($atts = array()): string
{
    return \DynamicOnlineServices\Shortcodes\Category::render($atts);
}
add_shortcode('service_category_content', 'doc_display_service_category_content_shortcode');

