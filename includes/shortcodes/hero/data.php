<?php
/**
 * Hero Shortcode Data Retrieval
 *
 * Handles data retrieval for hero shortcodes.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get hero data for category archive pages.
 *
 * @since 1.1.0
 * @param WP_Term $term Term object.
 * @return array Hero data array.
 */
function doc_get_category_hero_data($term): array {
    // Validate term object
    $term = doc_validate_term_object($term, 'services_category');
    if (false === $term) {
        return array();
    }
    
    $options = doc_get_options();
    
    // Get thumbnail with validation
    $thumbnail_id = get_term_meta($term->term_id, 'service_cat_thumbnail', true);
    $image_url    = '';
    if ($thumbnail_id) {
        $validated_thumbnail_id = doc_validate_attachment_id($thumbnail_id);
        if (false !== $validated_thumbnail_id) {
            $attachment_url = wp_get_attachment_image_url($validated_thumbnail_id, 'full');
            if ($attachment_url) {
                $image_url = esc_url($attachment_url);
            }
        }
    }

    // Get settings
    $hero_title_color  = doc_get_option($options, 'hero_title_color', '#FFFFFF');
    $hero_overlay_color = doc_get_option($options, 'hero_overlay_color', '#000000');
    $hero_font_family  = doc_get_option($options, 'hero_font_family', 'Helvetica');
    $hero_height       = doc_get_option($options, 'hero_height', '50vh');

    // Allow filtering values
    $hero_title_color  = apply_filters('doc_category_hero_title_color', $hero_title_color, $term);
    $hero_font_family  = apply_filters('doc_category_hero_font_family', $hero_font_family, $term);
    $image_url         = apply_filters('doc_category_hero_image_url', $image_url, $term);
    $hero_height       = apply_filters('doc_category_hero_height', $hero_height, $term);
    $title             = apply_filters('doc_category_hero_title', $term->name, $term);

    return array(
        'image_url'      => $image_url,
        'height'         => $hero_height,
        'title_color'    => $hero_title_color,
        'font_family'    => $hero_font_family,
        'title'          => $title,
        'description'    => '',
        'data_attribute' => 'data-term-id',
        'data_value'     => $term->term_id,
        'overlay_color'  => $hero_overlay_color,
    );
}

/**
 * Get hero data for single service pages.
 *
 * @since 1.1.0
 * @param WP_Post $post Post object.
 * @return array Hero data array.
 */
function doc_get_service_hero_data($post): array {
    $options = doc_get_options();
    $post_id = $post->ID;
    
    // Get banner image and description using consolidated helper functions
    $image_array = doc_get_service_banner_image($post_id);
    $description = doc_get_service_description($post_id);
    $post_title  = get_the_title($post_id);

    $image_url = '';
    if (is_array($image_array) && isset($image_array['url'])) {
        $image_url = esc_url($image_array['url']);
    }

    // Get settings
    $hero_title_color  = doc_get_option($options, 'hero_title_color', '#FFFFFF');
    $hero_overlay_color = doc_get_option($options, 'hero_overlay_color', '#000000');
    $hero_font_family  = doc_get_option($options, 'hero_font_family', 'Helvetica');
    $hero_height       = doc_get_option($options, 'hero_height', '50vh');

    // Allow filtering values
    $hero_title_color  = apply_filters('doc_service_hero_title_color', $hero_title_color, $post);
    $hero_font_family  = apply_filters('doc_service_hero_font_family', $hero_font_family, $post);
    $image_url         = apply_filters('doc_service_hero_image_url', $image_url, $post);
    $hero_height       = apply_filters('doc_service_hero_height', $hero_height, $post);
    $post_title        = apply_filters('doc_service_hero_title', $post_title, $post);
    $description       = apply_filters('doc_service_hero_description', $description, $post);

    return array(
        'image_url'      => $image_url,
        'height'         => $hero_height,
        'title_color'    => $hero_title_color,
        'font_family'    => $hero_font_family,
        'title'          => $post_title,
        'description'    => $description,
        'data_attribute' => 'data-post-id',
        'data_value'     => $post->ID,
        'overlay_color'  => $hero_overlay_color,
    );
}

