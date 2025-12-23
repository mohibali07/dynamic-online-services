<?php
/**
 * Service Cards Configuration
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Config
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cards configuration class.
 */
class Cards {

	/**
	 * Get service cards settings map.
	 *
	 * @return array Settings map.
	 */
	public static function get_map(): array {
		return array(
			'id'     => 'dynos_cards_section',
			'title'  => _x( 'Service Cards Settings', 'Settings section title', 'dynamic-online-services' ),
			'fields' => array(
				'card_bg_color'                => array(
					'title'    => __( 'Card Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#6A4B3F',
					'args'     => array(
						'description' => __( 'The background color of the service card.', 'dynamic-online-services' ),
					),
				),
				'card_title_color'             => array(
					'title'    => __( 'Card Title Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#FFFFFF',
					'args'     => array(
						'description' => __( 'Color of the main title within the card.', 'dynamic-online-services' ),
					),
				),
				'card_description_color'       => array(
					'title'    => __( 'Card Description Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#e0e0e0',
					'args'     => array(
						'description' => __( 'Color of the description text.', 'dynamic-online-services' ),
					),
				),
				'card_button_bg_color'         => array(
					'title'    => __( 'Card Button Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#0081A7',
					'args'     => array(
						'description' => __( 'Background color of the action button.', 'dynamic-online-services' ),
					),
				),
				'card_button_text_color'       => array(
					'title'    => __( 'Card Button Text Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#FFFFFF',
					'args'     => array(
						'description' => __( 'Text color of the action button.', 'dynamic-online-services' ),
					),
				),
				'card_button_hover_bg_color'   => array(
					'title'    => __( 'Card Button Hover Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#FFFFFF',
					'args'     => array(
						'description' => __( 'Button background color on hover state.', 'dynamic-online-services' ),
					),
				),
				'card_button_hover_text_color' => array(
					'title'    => __( 'Card Button Hover Text Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#0081A7',
					'args'     => array(
						'description' => __( 'Button text color on hover state.', 'dynamic-online-services' ),
					),
				),
				'card_font_family'             => array(
					'title'    => __( 'Card Font Family', 'dynamic-online-services' ),
					'callback' => 'dynos_font_family_field_callback',
					'default'  => 'sans-serif',
					'args'     => array(
						'description' => __( 'The font family to use for card text. Enter a standard font stack or Google Font name.', 'dynamic-online-services' ),
					),
				),
				'card_title_font_size'         => array(
					'title'    => __( 'Card Title Font Size', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '1.25rem',
					'args'     => array(
						'placeholder' => 'e.g., 1.25rem, 20px',
						'description' => __( 'Font size for the card title. Accepts keys like px, rem, em.', 'dynamic-online-services' ),
					),
				),
				'card_description_font_size'   => array(
					'title'    => __( 'Card Description Font Size', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0.9rem',
					'args'     => array(
						'placeholder' => 'e.g., 0.9rem, 14px',
						'description' => __( 'Font size for the card description text.', 'dynamic-online-services' ),
					),
				),
				'card_button_font_size'        => array(
					'title'    => __( 'Card Button Font Size', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0.9rem',
					'args'     => array(
						'placeholder' => 'e.g., 0.9rem, 14px',
						'description' => __( 'Font size for the button text.', 'dynamic-online-services' ),
					),
				),
				'card_border_radius'           => array(
					'title'    => __( 'Card Border Radius', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '18px',
					'args'     => array(
						'placeholder' => 'e.g., 18px, 1rem',
						'description' => __( 'Rounding of the card corners.', 'dynamic-online-services' ),
					),
				),
				'card_height'                  => array(
					'title'    => __( 'Card Height', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '400px',
					'args'     => array(
						'placeholder' => 'e.g., 400px, auto',
						'description' => __( 'Total height of the card element.', 'dynamic-online-services' ),
					),
				),
				'card_image_height'            => array(
					'title'    => __( 'Card Image Height', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '232px',
					'args'     => array(
						'placeholder' => 'e.g., 232px, 15rem',
						'description' => __( 'Height of the image area within the card.', 'dynamic-online-services' ),
					),
				),
				'card_content_height'          => array(
					'title'    => __( 'Card Content Height', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '200px',
					'args'     => array(
						'placeholder' => 'e.g., 200px, auto',
						'description' => __( 'Height of the text content area (title + description).', 'dynamic-online-services' ),
					),
				),
				'card_content_padding_top'     => array(
					'title'    => __( 'Card Content Padding Top', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '24px',
					'args'     => array(
						'placeholder' => 'e.g., 24px, 1.5rem',
						'description' => __( 'Space above the content inside the card.', 'dynamic-online-services' ),
					),
				),
				'card_content_padding_sides'   => array(
					'title'    => __( 'Card Content Padding Sides', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '10px',
					'args'     => array(
						'placeholder' => 'e.g., 10px, 0.5rem',
						'description' => __( 'Space on the left and right of the content.', 'dynamic-online-services' ),
					),
				),
				'card_grid_min_width'          => array(
					'title'    => __( 'Grid Item Minimum Width', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '280px',
					'args'     => array(
						'placeholder' => 'e.g., 280px, 18rem',
						'description' => __( 'Minimum width for each card in the grid. Controls responsive wrapping.', 'dynamic-online-services' ),
					),
				),
				'card_grid_column_gap'         => array(
					'title'    => __( 'Grid Column Gap', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '20px',
					'args'     => array(
						'placeholder' => 'e.g., 20px, 1.25rem',
						'description' => __( 'Horizontal space between cards in the grid.', 'dynamic-online-services' ),
					),
				),
				'card_grid_row_gap'            => array(
					'title'    => __( 'Grid Row Gap', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '40px',
					'args'     => array(
						'placeholder' => 'e.g., 40px, 2.5rem',
						'description' => __( 'Vertical space between rows of cards.', 'dynamic-online-services' ),
					),
				),
				'card_hover_transform'         => array(
					'title'    => __( 'Card Hover Transform', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => 'translateY(-5px)',
					'args'     => array(
						'placeholder' => 'e.g., translateY(-5px), scale(1.05)',
						'description' => __( 'CSS transform effect applied when hovering over a card.', 'dynamic-online-services' ),
					),
				),
				'card_hover_transition'        => array(
					'title'    => __( 'Card Hover Transition Speed', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0.2s',
					'args'     => array(
						'placeholder' => 'e.g., 0.2s, 300ms',
						'description' => __( 'Duration of the hover animation effect.', 'dynamic-online-services' ),
					),
				),
				'card_box_shadow'              => array(
					'title'    => __( 'Card Box Shadow', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0 4px 6px rgba(0, 0, 0, 0.1)',
					'args'     => array(
						'placeholder' => 'e.g., 0 4px 6px rgba(0, 0, 0, 0.1)',
						'description' => __( 'CSS box-shadow for the card. Leave empty to disable shadow.', 'dynamic-online-services' ),
					),
				),
			),
		);
	}
}
