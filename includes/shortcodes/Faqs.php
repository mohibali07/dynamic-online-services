<?php
/**
 * FAQs Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

namespace DynamicOnlineServices\Shortcodes;

use WP_Post;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Faqs class.
 */
class Faqs
{

    /**
     * Initialize shortcode.
     */
    public static function init(): void
    {
        add_shortcode('service_faqs_accordion', array(__CLASS__, 'render'));
    }

    /**
     * Render shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public static function render($atts = array()): string
    {
        // Include dependencies
        if (!function_exists('doc_render_faqs_accordion')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/faqs/renderer.php';
        }
        if (!function_exists('doc_get_service_faqs')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/faqs/data.php';
        }

        // Parse and validate shortcode attributes
        $default_title = __('Frequently Asked Questions', 'dynamic-online-services');

        if (function_exists('doc_parse_shortcode_attributes')) {
            $atts = doc_parse_shortcode_attributes(
                $atts,
                array('title' => $default_title),
                'service_faqs_accordion'
            );
        } else {
            $atts = shortcode_atts(
                array('title' => $default_title),
                $atts,
                'service_faqs_accordion'
            );
        }

        // Validate title attribute
        if (function_exists('doc_validate_faq_title_attribute')) {
            $atts['title'] = doc_validate_faq_title_attribute($atts['title'], $default_title);
        }

        // Check if we're on a service post
        if (!is_singular('service')) {
            return '';
        }

        // Get post object
        $post = get_queried_object();
        if (!$post || !($post instanceof WP_Post)) {
            $post = get_post();
        }

        // Validate post object
        if (function_exists('doc_validate_post_object')) {
            $post = doc_validate_post_object($post, 'service');
        }

        if (!$post) {
            return '';
        }

        $post_id = $post->ID;

        // Get FAQs
        $faqs = doc_get_service_faqs($post_id);

        if (empty($faqs)) {
            $empty_content = apply_filters('doc_faqs_empty_content', '', $post_id);
            return $empty_content;
        }

        // Render FAQ accordion
        $output = doc_render_faqs_accordion($faqs, $post_id, $atts['title']);

        // Allow filtering the final output
        return apply_filters('doc_faqs_output', $output, $faqs, $post_id);
    }
}
