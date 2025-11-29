<?php
/**
 * Hero Shortcode Class
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
 * Hero class.
 */
class Hero
{

    /**
     * Initialize shortcodes.
     */
    public static function init(): void
    {
        add_shortcode('service_category_hero', array(__CLASS__, 'render_category_hero'));
        add_shortcode('single_service_hero', array(__CLASS__, 'render_service_hero'));
    }

    /**
     * Render category hero shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public static function render_category_hero($atts = array()): string
    {
        // Include dependencies
        if (!function_exists('doc_process_hero_shortcode')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/renderer.php';
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/data.php';
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/shortcode-handler.php';
        }

        if (!is_tax('services_category')) {
            return '';
        }

        $queried_object = get_queried_object();
        if (function_exists('doc_validate_term_object')) {
            $queried_object = doc_validate_term_object($queried_object, 'services_category');
        }

        if (!$queried_object) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                do_action('doc_category_hero_term_error', get_queried_object());
            }
            return '';
        }

        // Allow filtering the queried object
        $queried_object = apply_filters('doc_category_hero_term', $queried_object);

        // Get hero data
        $hero_data = doc_get_category_hero_data($queried_object);

        // Process shortcode with common logic
        $output = doc_process_hero_shortcode($atts, $hero_data, 'service_category_hero');

        // Allow filtering the final output
        return apply_filters('doc_category_hero_output', $output, $queried_object);
    }

    /**
     * Render service hero shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public static function render_service_hero($atts = array()): string
    {
        // Include dependencies
        if (!function_exists('doc_process_hero_shortcode')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/renderer.php';
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/data.php';
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/hero/shortcode-handler.php';
        }

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

        // Get hero data
        $hero_data = doc_get_service_hero_data($post);

        // Process shortcode with common logic
        $output = doc_process_hero_shortcode($atts, $hero_data, 'single_service_hero');

        // Allow filtering the final output
        return apply_filters('doc_service_hero_output', $output, $post);
    }
}
