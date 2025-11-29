<?php
/**
 * FAQs Shortcode
 *
 * Main loader for FAQ accordion shortcode functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include FAQ shortcode components
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/faqs/renderer.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes/faqs/data.php';

/**
 * Shortcode to display FAQs as an accordion.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return string HTML output or empty string.
 */
function doc_display_service_faqs_accordion_shortcode($atts = array()): string
{
    return \DynamicOnlineServices\Shortcodes\Faqs::render($atts);
}
add_shortcode('service_faqs_accordion', 'doc_display_service_faqs_accordion_shortcode');

