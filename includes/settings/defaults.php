<?php
/**
 * Settings Defaults Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Settings;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Defaults class.
 */
class Defaults
{


	/**
	 * Get default options array.
	 *
	 * @return array Default option values.
	 */
	public static function get_options(): array
	{
		return array(
			// Hero Section Settings
			'hero_title_color' => '#FFFFFF',
			'hero_overlay_color' => '#000000',
			'hero_overlay_opacity' => '0.5',
			'hero_font_family' => 'sans-serif',
			'hero_title_font_size' => '3rem',
			'hero_title_font_size_tablet' => '2.5rem',
			'hero_title_font_size_mobile' => '2rem',
			'hero_description_font_size' => '1.25rem',
			'hero_description_font_size_tablet' => '1rem',
			'hero_description_font_size_mobile' => '0.9rem',
			'hero_height' => '50vh',
			'hero_height_tablet' => '30vh',
			'hero_height_mobile' => '30vh',
			'hero_content_padding' => '20px',
			'hero_border_radius' => '10px',
			'hero_margin_bottom' => '30px',

			// Breadcrumbs
			'rank_math_breadcrumbs' => true,

			// Service Cards Settings
			'card_bg_color' => '#6A4B3F',
			'card_title_color' => '#FFFFFF',
			'card_description_color' => '#e0e0e0',
			'card_button_bg_color' => '#0081A7',
			'card_button_text_color' => '#FFFFFF',
			'card_button_hover_bg_color' => '#FFFFFF',
			'card_button_hover_text_color' => '#0081A7',
			'card_font_family' => 'sans-serif',
			'card_title_font_size' => '1.25rem',
			'card_description_font_size' => '0.9rem',
			'card_button_font_size' => '0.9rem',
			'card_border_radius' => '18px',
			'card_height' => '400px',
			'card_image_height' => '232px',
			'card_content_height' => '200px',
			'card_content_padding_top' => '24px',
			'card_content_padding_sides' => '10px',
			'card_grid_gap' => '20px',
			'card_grid_column_gap' => '20px',
			'card_grid_row_gap' => '40px',
			'card_grid_min_width' => '280px',
			'card_hover_transform' => 'translateY(-5px)',
			'card_hover_transition' => '0.2s',
			'card_box_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',

			// FAQ Accordion Settings
			'faq_item_border_color' => '#ddd',
			'faq_item_border_radius' => '8px',
			'faq_item_margin_bottom' => '15px',
			'faq_item_box_shadow' => '0 2px 4px rgba(0, 0, 0, 0.05)',
			'faq_question_bg_color' => '#f7f7f7',
			'faq_question_bg_hover' => '#eee',
			'faq_question_text_color' => '#333',
			'faq_question_padding' => '15px 20px',
			'faq_question_font_size' => '1.15rem',
			'faq_icon_font_size' => '1.5rem',
			'faq_answer_padding' => '20px',
			'faq_answer_text_color' => '#333',
			'faq_answer_max_height' => '500px',
			'faq_transition_speed' => '0.4s',

			// Post Type & Taxonomy Settings
			'service_post_type_slug' => 'services',
			'service_taxonomy_slug' => 'service-category',
			'service_menu_position' => '',
			'service_menu_icon' => 'dashicons-admin-customizer',
			'service_cpt_singular_name' => 'Service',
			'service_cpt_plural_name' => 'Services',
			'service_tax_singular_name' => 'Service Category',
			'service_tax_plural_name' => 'Service Categories',
		);
	}
}
