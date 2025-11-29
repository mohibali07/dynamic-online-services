<?php
/**
 * FAQ Accordion Styles
 *
 * Handles enqueuing of FAQ accordion styles with dynamic options.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue FAQ accordion styles with dynamic options.
 *
 * @since 1.1.0
 */
function doc_enqueue_faq_accordion_styles(): void {
    // Check if we need to load FAQ styles
    $load_styles = false;

    // Get post object without using global variable
    $post = null;
    if (is_singular('service')) {
        $post = get_queried_object();
        if (!$post || !($post instanceof WP_Post)) {
            $post = get_post();
        }
        
        if ($post && isset($post->ID)) {
            $faqs = get_post_meta($post->ID, 'service_faqs', true);
            if (is_array($faqs) && !empty($faqs)) {
                $load_styles = true;
            }
        }
    }
    
    // Check if any post content has the shortcode
    if (!$load_styles) {
        $current_post = get_post();
        if ($current_post instanceof WP_Post && has_shortcode($current_post->post_content, 'service_faqs_accordion')) {
            $load_styles = true;
        }
    }

    if (!$load_styles) {
        return;
    }

    // Get options
    $options = doc_get_options();

    // Get FAQ settings
    $faq_border_color = doc_get_option($options, 'faq_item_border_color', '#ddd');
    $faq_border_radius = doc_get_option($options, 'faq_item_border_radius', '8px');
    $faq_margin_bottom = doc_get_option($options, 'faq_item_margin_bottom', '15px');
    $faq_box_shadow = doc_get_option($options, 'faq_item_box_shadow', '0 2px 4px rgba(0, 0, 0, 0.05)');
    $faq_question_bg = doc_get_option($options, 'faq_question_bg_color', '#f7f7f7');
    $faq_question_bg_hover = doc_get_option($options, 'faq_question_bg_hover', '#eee');
    $faq_question_text = doc_get_option($options, 'faq_question_text_color', '#333');
    $faq_answer_text = doc_get_option($options, 'faq_answer_text_color', '#333');
    $faq_question_padding = doc_get_option($options, 'faq_question_padding', '15px 20px');
    $faq_question_font_size = doc_get_option($options, 'faq_question_font_size', '1.15rem');
    $faq_icon_font_size = doc_get_option($options, 'faq_icon_font_size', '1.5rem');
    $faq_answer_padding = doc_get_option($options, 'faq_answer_padding', '20px');
    $faq_answer_max_height = doc_get_option($options, 'faq_answer_max_height', '500px');
    $faq_transition_speed = doc_get_option($options, 'faq_transition_speed', '0.4s');

    // Sanitize and escape all CSS values to prevent injection
    $faq_border_color = sanitize_hex_color($faq_border_color);
    $faq_border_radius = doc_escape_css_value($faq_border_radius, 'border-radius');
    $faq_margin_bottom = doc_escape_css_value($faq_margin_bottom, 'margin');
    $faq_box_shadow = doc_escape_css_value($faq_box_shadow, 'box-shadow');
    $faq_question_bg = sanitize_hex_color($faq_question_bg);
    $faq_question_bg_hover = sanitize_hex_color($faq_question_bg_hover);
    $faq_question_text = sanitize_hex_color($faq_question_text);
    $faq_answer_text = sanitize_hex_color($faq_answer_text);
    $faq_question_padding = doc_escape_css_value($faq_question_padding, 'padding');
    $faq_question_font_size = doc_escape_css_value($faq_question_font_size, 'font-size');
    $faq_icon_font_size = doc_escape_css_value($faq_icon_font_size, 'font-size');
    $faq_answer_padding = doc_escape_css_value($faq_answer_padding, 'padding');
    $faq_answer_max_height = doc_escape_css_value($faq_answer_max_height, 'max-height');
    $faq_transition_speed = doc_escape_css_value($faq_transition_speed, '');

    // Enqueue base FAQ styles
    wp_enqueue_style(
        'sos-faqs-accordion',
        DOC_PLUGIN_URL . 'assets/css/faqs-accordion.css',
        array(),
        DOC_VERSION
    );

    // Build dynamic CSS using safe CSS building functions
    $dynamic_css = '.faq-item {';
    $dynamic_css .= doc_build_css_rule('border', '1px solid ' . $faq_border_color);
    $dynamic_css .= doc_build_css_rule('border-radius', $faq_border_radius);
    $dynamic_css .= doc_build_css_rule('margin-bottom', $faq_margin_bottom);
    $dynamic_css .= doc_build_css_rule('box-shadow', $faq_box_shadow);
    $dynamic_css .= '}';
    
    $dynamic_css .= '.faq-question {';
    $dynamic_css .= doc_build_css_rule('background-color', $faq_question_bg);
    $dynamic_css .= doc_build_css_rule('color', $faq_question_text);
    $dynamic_css .= doc_build_css_rule('padding', $faq_question_padding);
    $dynamic_css .= doc_build_css_rule('font-size', $faq_question_font_size);
    $dynamic_css .= '}';
    
    $dynamic_css .= '.faq-question:hover {';
    $dynamic_css .= doc_build_css_rule('background-color', $faq_question_bg_hover);
    $dynamic_css .= '}';
    
    $dynamic_css .= '.faq-icon {';
    $dynamic_css .= doc_build_css_rule('font-size', $faq_icon_font_size);
    $dynamic_css .= doc_build_css_rule('transition', 'transform ' . $faq_transition_speed . ' ease');
    $dynamic_css .= '}';
    
    $dynamic_css .= '.faq-answer {';
    $dynamic_css .= doc_build_css_rule('color', $faq_answer_text);
    $dynamic_css .= doc_build_css_rule('transition', 'max-height ' . $faq_transition_speed . ' ease-out, padding ' . $faq_transition_speed . ' ease-out');
    $dynamic_css .= '}';
    
    $dynamic_css .= '.faq-answer.show {';
    $dynamic_css .= doc_build_css_rule('max-height', $faq_answer_max_height);
    $dynamic_css .= doc_build_css_rule('padding', $faq_answer_padding);
    $dynamic_css .= '}';

    // Allow filtering the CSS
    $dynamic_css = apply_filters('doc_faq_dynamic_css', $dynamic_css, $options);

    wp_add_inline_style('sos-faqs-accordion', $dynamic_css);
}
add_action('wp_enqueue_scripts', 'doc_enqueue_faq_accordion_styles');

