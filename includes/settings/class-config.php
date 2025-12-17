<?php
/**
 * Settings Configuration Class
 *
 * Defines the structure of all settings sections and fields.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Config class.
 */
class Config {

	/**
	 * Get all settings sections and fields.
	 *
	 * @return array Configuration array.
	 */
	public static function get_map(): array {
		return [
			// Post Type & Taxonomy Settings
			'post_type' => [
				'id'     => 'dynos_post_type_section',
				'title'  => __( 'Post Type & Taxonomy Settings', 'dynamic-online-services' ),
				'fields' => [
					'service_post_type_slug'    => [
						'title'       => _x( 'Service Post Type Slug', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'services',
						'args'        => [
							'placeholder' => 'e.g., services, my-services',
							'description' => __( 'URL slug for the service post type. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
						],
					],
					'service_taxonomy_slug'     => [
						'title'       => _x( 'Service Taxonomy Slug', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'service-category',
						'args'        => [
							'placeholder' => 'e.g., service-category, category',
							'description' => __( 'URL slug for the service category taxonomy. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
						],
					],
					'service_menu_position'     => [
						'title'       => _x( 'Service Menu Position', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => '',
						'args'        => [
							'placeholder' => 'e.g., 20, 30 (leave empty for default)',
							'description' => __( 'Menu position in WordPress admin. Lower numbers appear first. Leave empty for default position.', 'dynamic-online-services' ),
						],
					],
					'service_menu_icon'         => [
						'title'       => _x( 'Service Menu Icon', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'dashicons-admin-customizer',
						'args'        => [
							'placeholder' => 'e.g., dashicons-admin-customizer',
							'description' => __( 'Dashicon class name for the menu icon. See <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicons</a>.', 'dynamic-online-services' ),
						],
					],
					'service_cpt_singular_name' => [
						'title'       => _x( 'Service Singular Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service',
						'args'        => [
							'placeholder' => 'e.g., Service, Course',
							'description' => __( 'Singular name for the post type.', 'dynamic-online-services' ),
						],
					],
					'service_cpt_plural_name'   => [
						'title'       => _x( 'Service Plural Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Services',
						'args'        => [
							'placeholder' => 'e.g., Services, Courses',
							'description' => __( 'Plural name for the post type.', 'dynamic-online-services' ),
						],
					],
					'service_tax_singular_name' => [
						'title'       => _x( 'Category Singular Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service Category',
						'args'        => [
							'placeholder' => 'e.g., Category, Topic',
							'description' => __( 'Singular name for the taxonomy.', 'dynamic-online-services' ),
						],
					],
					'service_tax_plural_name'   => [
						'title'       => _x( 'Category Plural Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service Categories',
						'args'        => [
							'placeholder' => 'e.g., Categories, Topics',
							'description' => __( 'Plural name for the taxonomy.', 'dynamic-online-services' ),
						],
					],
				],
			],

			// Hero Section Settings
			'hero'      => [
				'id'     => 'dynos_hero_section',
				'title'  => __( 'Hero Section Settings', 'dynamic-online-services' ),
				'fields' => [
					'hero_title_color'                  => [
						'title'    => __( 'Hero Title Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#FFFFFF',
						'args'     => [
							'description' => __( 'Color of the main hero title.', 'dynamic-online-services' ),
						],
					],
					'hero_overlay_color'                => [
						'title'    => __( 'Hero Overlay Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#000000',
						'args'     => [
							'description' => __( 'This color will be semi-transparent.', 'dynamic-online-services' ),
						],
					],
					'hero_overlay_opacity'              => [
						'title'    => __( 'Hero Overlay Opacity', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '0.5',
						'args'     => [
							'min'         => '0',
							'max'         => '1',
							'step'        => '0.1',
							'description' => __( 'Overlay opacity from 0 (transparent) to 1 (opaque).', 'dynamic-online-services' ),
						],
					],
					'hero_font_family'                  => [
						'title'    => __( 'Hero Font Family', 'dynamic-online-services' ),
						'callback' => 'dynos_font_family_field_callback',
						'default'  => 'sans-serif',
						'args'     => [
							'description' => __( 'You can use a standard font or a Google Font name.', 'dynamic-online-services' ),
						],
					],
					'hero_height'                       => [
						'title'    => __( 'Hero Height (Desktop)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '50vh',
						'args'     => [
							'placeholder' => 'e.g., 50vh, 400px',
							'description' => __( 'Hero section height for desktop. Use px, vh, or %.', 'dynamic-online-services' ),
						],
					],
					'hero_height_tablet'                => [
						'title'    => __( 'Hero Height (Tablet)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '30vh',
						'args'     => [
							'placeholder' => 'e.g., 30vh, 300px',
							'description' => __( 'Hero section height for tablets (max-width: 768px).', 'dynamic-online-services' ),
						],
					],
					'hero_height_mobile'                => [
						'title'    => __( 'Hero Height (Mobile)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '30vh',
						'args'     => [
							'placeholder' => 'e.g., 30vh, 250px',
							'description' => __( 'Hero section height for mobile devices (max-width: 480px).', 'dynamic-online-services' ),
						],
					],
					'hero_title_font_size'              => [
						'title'    => __( 'Hero Title Font Size (Desktop)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '3rem',
						'args'     => [
							'placeholder' => 'e.g., 3rem, 48px',
							'description' => __( 'Font size for the hero title on desktop.', 'dynamic-online-services' ),
						],
					],
					'hero_title_font_size_tablet'       => [
						'title'    => __( 'Hero Title Font Size (Tablet)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '2.5rem',
						'args'     => [
							'placeholder' => 'e.g., 2.5rem, 40px',
							'description' => __( 'Font size for the hero title on tablets.', 'dynamic-online-services' ),
						],
					],
					'hero_title_font_size_mobile'       => [
						'title'    => __( 'Hero Title Font Size (Mobile)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '2rem',
						'args'     => [
							'placeholder' => 'e.g., 2rem, 32px',
							'description' => __( 'Font size for the hero title on mobile.', 'dynamic-online-services' ),
						],
					],
					'hero_description_font_size'        => [
						'title'    => __( 'Hero Description Font Size (Desktop)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '1.25rem',
						'args'     => [
							'placeholder' => 'e.g., 1.25rem, 20px',
							'description' => __( 'Font size for the hero description on desktop.', 'dynamic-online-services' ),
						],
					],
					'hero_description_font_size_tablet' => [
						'title'    => __( 'Hero Description Font Size (Tablet)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '1rem',
						'args'     => [
							'placeholder' => 'e.g., 1rem, 16px',
							'description' => __( 'Font size for the hero description on tablets.', 'dynamic-online-services' ),
						],
					],
					'hero_description_font_size_mobile' => [
						'title'    => __( 'Hero Description Font Size (Mobile)', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0.9rem',
						'args'     => [
							'placeholder' => 'e.g., 0.9rem, 14px',
							'description' => __( 'Font size for the hero description on mobile.', 'dynamic-online-services' ),
						],
					],
					'hero_content_padding'              => [
						'title'    => __( 'Hero Content Padding', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '20px',
						'args'     => [
							'placeholder' => 'e.g., 20px, 1rem 2rem',
							'description' => __( 'Padding inside the hero container.', 'dynamic-online-services' ),
						],
					],
					'hero_border_radius'                => [
						'title'    => __( 'Hero Content Border Radius', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '10px',
						'args'     => [
							'placeholder' => 'e.g., 10px, 0.5rem',
							'description' => __( 'Rounding of the hero content box corners.', 'dynamic-online-services' ),
						],
					],
					'hero_margin_bottom'                => [
						'title'    => __( 'Hero Margin Bottom', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '30px',
						'args'     => [
							'placeholder' => 'e.g., 30px, 2rem',
							'description' => __( 'Space below the hero section.', 'dynamic-online-services' ),
						],
					],
					'rank_math_breadcrumbs'             => [
						'title'    => __( 'Enable Rank Math Breadcrumbs', 'dynamic-online-services' ),
						'callback' => 'dynos_checkbox_field_callback',
						'default'  => true,
						'args'     => [
							'label'       => __( 'Show Breadcrumbs in Hero Section', 'dynamic-online-services' ),
							'description' => __( 'Requires Rank Math SEO plugin to be active.', 'dynamic-online-services' ),
						],
					],
				],
			],

			// Service Cards Settings
			'cards'     => [
				'id'     => 'dynos_cards_section',
				'title'  => _x( 'Service Cards Settings', 'Settings section title', 'dynamic-online-services' ),
				'fields' => [
					'card_bg_color'                => [
						'title'    => __( 'Card Background Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#6A4B3F',
						'args'     => [
							'description' => __( 'The background color of the service card.', 'dynamic-online-services' ),
						],
					],
					'card_title_color'             => [
						'title'    => __( 'Card Title Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#FFFFFF',
						'args'     => [
							'description' => __( 'Color of the main title within the card.', 'dynamic-online-services' ),
						],
					],
					'card_description_color'       => [
						'title'    => __( 'Card Description Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#e0e0e0',
						'args'     => [
							'description' => __( 'Color of the description text.', 'dynamic-online-services' ),
						],
					],
					'card_button_bg_color'         => [
						'title'    => __( 'Card Button Background Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#0081A7',
						'args'     => [
							'description' => __( 'Background color of the action button.', 'dynamic-online-services' ),
						],
					],
					'card_button_text_color'       => [
						'title'    => __( 'Card Button Text Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#FFFFFF',
						'args'     => [
							'description' => __( 'Text color of the action button.', 'dynamic-online-services' ),
						],
					],
					'card_button_hover_bg_color'   => [
						'title'    => __( 'Card Button Hover Background Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#FFFFFF',
						'args'     => [
							'description' => __( 'Button background color on hover state.', 'dynamic-online-services' ),
						],
					],
					'card_button_hover_text_color' => [
						'title'    => __( 'Card Button Hover Text Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#0081A7',
						'args'     => [
							'description' => __( 'Button text color on hover state.', 'dynamic-online-services' ),
						],
					],
					'card_button_text'             => [
						'title'    => __( 'Card Button Text', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => 'VIEW DETAILS',
						'args'     => [
							'placeholder' => 'e.g., VIEW DETAILS, READ MORE',
							'description' => __( 'Text to display on the action button.', 'dynamic-online-services' ),
						],
					],
					'card_font_family'             => [
						'title'    => __( 'Card Font Family', 'dynamic-online-services' ),
						'callback' => 'dynos_font_family_field_callback',
						'default'  => 'sans-serif',
						'args'     => [
							'description' => __( 'The font family to use for card text. Enter a standard font stack or Google Font name.', 'dynamic-online-services' ),
						],
					],
					'card_title_font_size'         => [
						'title'    => __( 'Card Title Font Size', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '1.25rem',
						'args'     => [
							'placeholder' => 'e.g., 1.25rem, 20px',
							'description' => __( 'Font size for the card title. Accepts keys like px, rem, em.', 'dynamic-online-services' ),
						],
					],
					'card_description_font_size'   => [
						'title'    => __( 'Card Description Font Size', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0.9rem',
						'args'     => [
							'placeholder' => 'e.g., 0.9rem, 14px',
							'description' => __( 'Font size for the card description text.', 'dynamic-online-services' ),
						],
					],
					'card_button_font_size'        => [
						'title'    => __( 'Card Button Font Size', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0.9rem',
						'args'     => [
							'placeholder' => 'e.g., 0.9rem, 14px',
							'description' => __( 'Font size for the button text.', 'dynamic-online-services' ),
						],
					],
					'card_border_radius'           => [
						'title'    => __( 'Card Border Radius', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '18px',
						'args'     => [
							'placeholder' => 'e.g., 18px, 1rem',
							'description' => __( 'Rounding of the card corners.', 'dynamic-online-services' ),
						],
					],
					'card_height'                  => [
						'title'    => __( 'Card Height', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '400px',
						'args'     => [
							'placeholder' => 'e.g., 400px, auto',
							'description' => __( 'Total height of the card element.', 'dynamic-online-services' ),
						],
					],
					'card_image_height'            => [
						'title'    => __( 'Card Image Height', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '232px',
						'args'     => [
							'placeholder' => 'e.g., 232px, 15rem',
							'description' => __( 'Height of the image area within the card.', 'dynamic-online-services' ),
						],
					],
					'card_content_height'          => [
						'title'    => __( 'Card Content Height', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '200px',
						'args'     => [
							'placeholder' => 'e.g., 200px, auto',
							'description' => __( 'Height of the text content area (title + description).', 'dynamic-online-services' ),
						],
					],
					'card_content_padding_top'     => [
						'title'    => __( 'Card Content Padding Top', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '24px',
						'args'     => [
							'placeholder' => 'e.g., 24px, 1.5rem',
							'description' => __( 'Space above the content inside the card.', 'dynamic-online-services' ),
						],
					],
					'card_content_padding_sides'   => [
						'title'    => __( 'Card Content Padding Sides', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '10px',
						'args'     => [
							'placeholder' => 'e.g., 10px, 0.5rem',
							'description' => __( 'Space on the left and right of the content.', 'dynamic-online-services' ),
						],
					],
					'card_grid_min_width'          => [
						'title'    => __( 'Grid Item Minimum Width', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '280px',
						'args'     => [
							'placeholder' => 'e.g., 280px, 18rem',
							'description' => __( 'Minimum width for each card in the grid. Controls responsive wrapping.', 'dynamic-online-services' ),
						],
					],
					'card_grid_column_gap'         => [
						'title'    => __( 'Grid Column Gap', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '20px',
						'args'     => [
							'placeholder' => 'e.g., 20px, 1.25rem',
							'description' => __( 'Horizontal space between cards in the grid.', 'dynamic-online-services' ),
						],
					],
					'card_grid_row_gap'            => [
						'title'    => __( 'Grid Row Gap', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '40px',
						'args'     => [
							'placeholder' => 'e.g., 40px, 2.5rem',
							'description' => __( 'Vertical space between rows of cards.', 'dynamic-online-services' ),
						],
					],
					'card_hover_transform'         => [
						'title'    => __( 'Card Hover Transform', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => 'translateY(-5px)',
						'args'     => [
							'placeholder' => 'e.g., translateY(-5px), scale(1.05)',
							'description' => __( 'CSS transform effect applied when hovering over a card.', 'dynamic-online-services' ),
						],
					],
					'card_hover_transition'        => [
						'title'    => __( 'Card Hover Transition Speed', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0.2s',
						'args'     => [
							'placeholder' => 'e.g., 0.2s, 300ms',
							'description' => __( 'Duration of the hover animation effect.', 'dynamic-online-services' ),
						],
					],
					'card_box_shadow'              => [
						'title'    => __( 'Card Box Shadow', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0 4px 6px rgba(0, 0, 0, 0.1)',
						'args'     => [
							'placeholder' => 'e.g., 0 4px 6px rgba(0, 0, 0, 0.1)',
							'description' => __( 'CSS box-shadow for the card. Leave empty to disable shadow.', 'dynamic-online-services' ),
						],
					],
				],
			],

			// FAQ Accordion Settings
			'faq'       => [
				'id'     => 'dynos_faq_section',
				'title'  => __( 'FAQ Accordion Settings', 'dynamic-online-services' ),
				'fields' => [
					'faq_item_border_color'   => [
						'title'    => __( 'FAQ Item Border Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#ddd',
						'args'     => [
							'description' => __( 'Color of the border around each FAQ item.', 'dynamic-online-services' ),
						],
					],
					'faq_question_bg_color'   => [
						'title'    => __( 'FAQ Question Background Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#f7f7f7',
						'args'     => [
							'description' => __( 'Background color of the question area (collapsed state).', 'dynamic-online-services' ),
						],
					],
					'faq_question_bg_hover'   => [
						'title'    => __( 'FAQ Question Hover Background Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#eee',
						'args'     => [
							'description' => __( 'Background color when hovering over the question.', 'dynamic-online-services' ),
						],
					],
					'faq_question_text_color' => [
						'title'    => __( 'FAQ Question Text Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#333',
						'args'     => [
							'description' => __( 'Text color of the question.', 'dynamic-online-services' ),
						],
					],
					'faq_answer_text_color'   => [
						'title'    => __( 'FAQ Answer Text Color', 'dynamic-online-services' ),
						'callback' => 'dynos_color_field_callback',
						'default'  => '#333',
						'args'     => [
							'description' => __( 'Text color of the answer content.', 'dynamic-online-services' ),
						],
					],
					'faq_item_border_radius'  => [
						'title'    => __( 'FAQ Item Border Radius', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '8px',
						'args'     => [
							'placeholder' => 'e.g., 8px, 0.5rem',
							'description' => __( 'Rounding of the FAQ item corners.', 'dynamic-online-services' ),
						],
					],
					'faq_item_margin_bottom'  => [
						'title'    => __( 'FAQ Item Margin Bottom', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '15px',
						'args'     => [
							'placeholder' => 'e.g., 15px, 1rem',
							'description' => __( 'Space between FAQ items.', 'dynamic-online-services' ),
						],
					],
					'faq_item_box_shadow'     => [
						'title'    => __( 'FAQ Item Box Shadow', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0 2px 4px rgba(0, 0, 0, 0.05)',
						'args'     => [
							'placeholder' => 'e.g., 0 2px 4px rgba(0, 0, 0, 0.05)',
							'description' => __( 'CSS box-shadow value for each item. Leave empty to disable.', 'dynamic-online-services' ),
						],
					],
					'faq_question_font_size'  => [
						'title'    => __( 'FAQ Question Font Size', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '1.15rem',
						'args'     => [
							'placeholder' => 'e.g., 1.15rem, 18px',
							'description' => __( 'Font size for the question text.', 'dynamic-online-services' ),
						],
					],
					'faq_icon_font_size'      => [
						'title'    => __( 'FAQ Icon Font Size', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '1.5rem',
						'args'     => [
							'placeholder' => 'e.g., 1.5rem, 24px',
							'description' => __( 'Size of the toggle icon (+/-).', 'dynamic-online-services' ),
						],
					],
					'faq_question_padding'    => [
						'title'    => __( 'FAQ Question Padding', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '15px 20px',
						'args'     => [
							'placeholder' => 'e.g., 15px 20px, 1rem 1.25rem',
							'description' => __( 'Internal spacing for the question area.', 'dynamic-online-services' ),
						],
					],
					'faq_answer_padding'      => [
						'title'    => __( 'FAQ Answer Padding', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '20px',
						'args'     => [
							'placeholder' => 'e.g., 20px, 1.25rem',
							'description' => __( 'Internal spacing for the answer content.', 'dynamic-online-services' ),
						],
					],
					'faq_answer_max_height'   => [
						'title'    => __( 'FAQ Answer Max Height', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '500px',
						'args'     => [
							'placeholder' => 'e.g., 500px, none',
							'description' => __( 'Maximum height for the answer block. Used for the slide-down animation. Ensure it is large enough to fit your content.', 'dynamic-online-services' ),
						],
					],
					'faq_transition_speed'    => [
						'title'    => __( 'FAQ Transition Speed', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '0.4s',
						'args'     => [
							'placeholder' => 'e.g., 0.4s, 400ms',
							'description' => __( 'Speed of the open/close animation.', 'dynamic-online-services' ),
						],
					],
				],
			],

			// Advanced Configuration Settings
			'advanced'  => [
				'id'     => 'dynos_advanced_section',
				'title'  => __( 'Advanced Configuration', 'dynamic-online-services' ),
				'fields' => [
					'breakpoint_tablet'         => [
						'title'    => __( 'Tablet Breakpoint', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '768px',
						'args'     => [
							'placeholder' => 'e.g., 768px, 1024px',
							'description' => __( 'CSS max-width for tablet devices. Used in responsive media queries.', 'dynamic-online-services' ),
						],
					],
					'breakpoint_mobile'         => [
						'title'    => __( 'Mobile Breakpoint', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '480px',
						'args'     => [
							'placeholder' => 'e.g., 480px, 600px',
							'description' => __( 'CSS max-width for mobile devices. Used in responsive media queries.', 'dynamic-online-services' ),
						],
					],
					'max_grid_columns'          => [
						'title'    => __( 'Maximum Grid Columns', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '6',
						'args'     => [
							'min'         => '1',
							'max'         => '12',
							'step'        => '1',
							'description' => __( 'Maximum number of columns in grid layouts.', 'dynamic-online-services' ),
						],
					],
					'min_grid_columns'          => [
						'title'    => __( 'Minimum Grid Columns', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '1',
						'args'     => [
							'min'         => '1',
							'max'         => '6',
							'step'        => '1',
							'description' => __( 'Minimum number of columns in grid layouts.', 'dynamic-online-services' ),
						],
					],
					'max_taxonomy_depth'        => [
						'title'    => __( 'Maximum Taxonomy Depth', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '10',
						'args'     => [
							'min'         => '1',
							'max'         => '20',
							'step'        => '1',
							'description' => __( 'Maximum hierarchy depth for category permalinks. Prevents infinite loops.', 'dynamic-online-services' ),
						],
					],
					'default_excerpt_length'    => [
						'title'    => __( 'Default Excerpt Length', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '20',
						'args'     => [
							'min'         => '5',
							'max'         => '100',
							'step'        => '1',
							'description' => __( 'Number of words in automatically generated excerpts.', 'dynamic-online-services' ),
						],
					],
					'max_posts_per_page'        => [
						'title'    => __( 'Maximum Posts Per Page', 'dynamic-online-services' ),
						'callback' => 'dynos_number_field_callback',
						'default'  => '100',
						'args'     => [
							'min'         => '10',
							'max'         => '5000',
							'step'        => '10',
							'description' => __( 'Maximum posts to query at once. Prevents performance issues.', 'dynamic-online-services' ),
						],
					],
					'default_grid_min_width'    => [
						'title'    => __( 'Default Grid Minimum Width', 'dynamic-online-services' ),
						'callback' => 'dynos_text_field_callback',
						'default'  => '280px',
						'args'     => [
							'placeholder' => 'e.g., 280px, 18rem',
							'description' => __( 'Minimum width for grid items. Controls responsive wrapping.', 'dynamic-online-services' ),
						],
					],
					'google_font_hosting'       => [
						'title'    => __( 'Google Font Hosting Method', 'dynamic-online-services' ),
						'callback' => 'dynos_radio_field_callback',
						'default'  => 'remote',
						'args'     => [
							'options'     => [
								'remote' => __( 'Remote (Google CDN - Faster but sends data to Google)', 'dynamic-online-services' ),
								'local'  => __( 'Local (GDPR Compliant - Fonts hosted on your server)', 'dynamic-online-services' ),
							],
							'description' => __( 'Choose how Google Fonts are loaded. Local hosting improves GDPR compliance but may be slower on first load.', 'dynamic-online-services' ),
						],
					],
				],
			],
		];
	}
}
