<?php
/**
 * Hero Shortcode Data Retrieval
 *
 * Handles data retrieval for hero shortcodes.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

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
function dynos_get_category_hero_data($term): array
{
	$settings = function_exists('dynos_sanitize_cpt_settings') ? dynos_sanitize_cpt_settings() : array();
	$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

	// Validate term object
	$term = dynos_validate_term_object($term, $taxonomy_slug);
	if (false === $term) {
		return array();
	}

	$options = \DynamicOnlineServices\Helpers\Options::get();

	// Get thumbnail with validation
	$thumbnail_id = get_term_meta($term->term_id, 'service_cat_thumbnail', true);
	$image_url = '';
	if ($thumbnail_id) {
		$validated_thumbnail_id = dynos_validate_attachment_id($thumbnail_id);
		if (false !== $validated_thumbnail_id) {
			$attachment_url = wp_get_attachment_image_url($validated_thumbnail_id, 'full');
			if ($attachment_url) {
				$image_url = esc_url($attachment_url);
			}
		}
	}

	// Get settings
	$hero_title_color = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_title_color', '#FFFFFF');
	$hero_overlay_color = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_overlay_color', '#000000');
	$hero_font_family = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_font_family', 'Helvetica');
	$hero_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_height', '50vh');

	// Allow filtering values
	$hero_title_color = apply_filters('dynos_category_hero_title_color', $hero_title_color, $term);
	$hero_font_family = apply_filters('dynos_category_hero_font_family', $hero_font_family, $term);
	$image_url = apply_filters('dynos_category_hero_image_url', $image_url, $term);
	$hero_height = apply_filters('dynos_category_hero_height', $hero_height, $term);
	$title = apply_filters('dynos_category_hero_title', $term->name, $term);

	return array(
		'image_url' => $image_url,
		'height' => $hero_height,
		'title_color' => $hero_title_color,
		'font_family' => $hero_font_family,
		'title' => $title,
		'description' => '',
		'data_attribute' => 'data-term-id',
		'data_value' => $term->term_id,
		'overlay_color' => $hero_overlay_color,
	);
}

/**
 * Get hero data for single service pages.
 *
 * @since 1.1.0
 * @param WP_Post $post Post object.
 * @return array Hero data array.
 */
function dynos_get_service_hero_data($post): array
{
	$options = \DynamicOnlineServices\Helpers\Options::get();
	$post_id = $post->ID;

	// Get banner image and description using consolidated helper functions
	$image_array = dynos_get_service_banner_image($post_id);
	$description = dynos_get_service_description($post_id);
	$post_title = get_the_title($post_id);

	$image_url = '';
	if (is_array($image_array) && isset($image_array['url'])) {
		$image_url = esc_url($image_array['url']);
	}

	// Get settings
	$hero_title_color = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_title_color', '#FFFFFF');
	$hero_overlay_color = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_overlay_color', '#000000');
	$hero_font_family = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_font_family', 'Helvetica');
	$hero_height = \DynamicOnlineServices\Helpers\Options::get_option($options, 'hero_height', '50vh');

	// Allow filtering values
	$hero_title_color = apply_filters('dynos_service_hero_title_color', $hero_title_color, $post);
	$hero_font_family = apply_filters('dynos_service_hero_font_family', $hero_font_family, $post);
	$image_url = apply_filters('dynos_service_hero_image_url', $image_url, $post);
	$hero_height = apply_filters('dynos_service_hero_height', $hero_height, $post);
	$post_title = apply_filters('dynos_service_hero_title', $post_title, $post);
	$description = apply_filters('dynos_service_hero_description', $description, $post);

	return array(
		'image_url' => $image_url,
		'height' => $hero_height,
		'title_color' => $hero_title_color,
		'font_family' => $hero_font_family,
		'title' => $post_title,
		'description' => $description,
		'data_attribute' => 'data-post-id',
		'data_value' => $post->ID,
		'overlay_color' => $hero_overlay_color,
	);
}
