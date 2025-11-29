<?php
/**
 * Category Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

namespace DynamicOnlineServices\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Category class.
 */
class Category
{

    /**
     * Initialize shortcode.
     */
    public static function init(): void
    {
        add_shortcode('service_category_content', array(__CLASS__, 'render'));
    }

    /**
     * Render shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public static function render($atts = array()): string
    {
        // Include dependencies if not already loaded (legacy support)
        if (!function_exists('doc_validate_category_shortcode_attributes')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/validation.php';
        }
        if (!function_exists('doc_get_category_shortcode_child_categories')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/query.php';
        }
        if (!function_exists('doc_render_category_shortcode_items')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/renderer.php';
        }
        if (!function_exists('doc_get_pagination_html')) {
            require_once DOC_PLUGIN_DIR . 'includes/shortcodes/category/pagination.php';
        }

        // Parse shortcode attributes
        $atts = shortcode_atts(
            array(
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'hide_empty' => false,
                'columns' => 'auto',
                'min_width' => '',
                'pagination' => false,
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            ),
            $atts,
            'service_category_content'
        );

        // Validate and sanitize attributes
        $atts = doc_validate_category_shortcode_attributes($atts);

        if (!is_tax('services_category')) {
            return '';
        }

        $term = get_queried_object();
        // Use global helper if available, otherwise basic validation
        if (function_exists('doc_validate_term_object')) {
            $term = doc_validate_term_object($term, 'services_category');
        }

        if (!$term) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                do_action('doc_category_content_term_error', get_queried_object());
            }
            return '';
        }

        // Allow filtering the term
        $term = apply_filters('doc_category_content_term', $term);

        // Enqueue card styles using dedicated helper
        if (function_exists('doc_enqueue_service_card_styles_asset')) {
            doc_enqueue_service_card_styles_asset();
        }

        // Get child categories
        $child_category_items = doc_get_category_shortcode_child_categories($term, $atts['hide_empty']);

        // Get services
        $services_data = doc_get_category_shortcode_services($term, $atts);
        $service_items = $services_data['items'];
        $total_pages = $services_data['total_pages'];
        $current_page = $services_data['current_page'];

        // Combine all items
        $items_to_display = array_merge($child_category_items, $service_items);

        // Allow filtering items before display
        $items_to_display = apply_filters('doc_category_content_items', $items_to_display, $term);

        // Render items
        $output = doc_render_category_shortcode_items($items_to_display, $term, $atts);

        // Add pagination navigation if enabled
        $pagination_html = '';
        if ($atts['pagination'] && $atts['posts_per_page'] > 0 && $total_pages > 1) {
            $pagination_html = doc_get_pagination_html($current_page, $total_pages);
            $pagination_html = apply_filters('doc_category_content_pagination', $pagination_html, $current_page, $total_pages, $term);
        }

        // Combine output and pagination
        $final_output = $output . $pagination_html;

        // Allow filtering the final output
        return apply_filters('doc_category_content_output', $final_output, $items_to_display, $term);
    }
}
