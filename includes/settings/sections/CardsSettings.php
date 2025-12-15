<?php
/**
 * Cards Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Sections
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Settings\Sections;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CardsSettings class.
 */
class CardsSettings {


	/**
	 * Register settings.
	 */
	public static function register(): void {
		// Card Colors.
		add_settings_field(
			'card_bg_color',
			__( 'Card Background Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_bg_color',
				'default'   => '#6A4B3F',
				'label_for' => 'card_bg_color',
			)
		);

		add_settings_field(
			'card_title_color',
			__( 'Card Title Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_title_color',
				'default'   => '#FFFFFF',
				'label_for' => 'card_title_color',
			)
		);

		add_settings_field(
			'card_description_color',
			__( 'Card Description Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_description_color',
				'default'   => '#e0e0e0',
				'label_for' => 'card_description_color',
			)
		);

		add_settings_field(
			'card_button_bg_color',
			__( 'Card Button Background Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_button_bg_color',
				'default'   => '#0081A7',
				'label_for' => 'card_button_bg_color',
			)
		);

		add_settings_field(
			'card_button_text_color',
			__( 'Card Button Text Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_button_text_color',
				'default'   => '#FFFFFF',
				'label_for' => 'card_button_text_color',
			)
		);

		add_settings_field(
			'card_button_hover_bg_color',
			__( 'Card Button Hover Background Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_button_hover_bg_color',
				'default'   => '#FFFFFF',
				'label_for' => 'card_button_hover_bg_color',
			)
		);

		add_settings_field(
			'card_button_hover_text_color',
			__( 'Card Button Hover Text Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'      => 'card_button_hover_text_color',
				'default'   => '#0081A7',
				'label_for' => 'card_button_hover_text_color',
			)
		);

		add_settings_field(
			'card_font_family',
			__( 'Card Font Family', 'dynamic-online-services' ),
			'dynos_font_family_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_font_family',
				'default'     => 'sans-serif',
				'description' => __( 'You can use a standard font or a Google Font name.', 'dynamic-online-services' ),
				'label_for'   => 'card_font_family',
			)
		);

		// Card Typography.
		add_settings_field(
			'card_title_font_size',
			__( 'Card Title Font Size', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_title_font_size',
				'default'     => '1.25rem',
				'placeholder' => 'e.g., 1.25rem, 20px',
				'label_for'   => 'card_title_font_size',
			)
		);

		add_settings_field(
			'card_description_font_size',
			__( 'Card Description Font Size', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_description_font_size',
				'default'     => '0.9rem',
				'placeholder' => 'e.g., 0.9rem, 14px',
				'label_for'   => 'card_description_font_size',
			)
		);

		add_settings_field(
			'card_button_font_size',
			__( 'Card Button Font Size', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_button_font_size',
				'default'     => '0.9rem',
				'placeholder' => 'e.g., 0.9rem, 14px',
				'label_for'   => 'card_button_font_size',
			)
		);

		// Card Dimensions.
		add_settings_field(
			'card_border_radius',
			__( 'Card Border Radius', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_border_radius',
				'default'     => '18px',
				'placeholder' => 'e.g., 18px, 1rem',
				'label_for'   => 'card_border_radius',
			)
		);

		add_settings_field(
			'card_height',
			__( 'Card Height', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_height',
				'default'     => '400px',
				'placeholder' => 'e.g., 400px, auto',
				'label_for'   => 'card_height',
			)
		);

		add_settings_field(
			'card_image_height',
			__( 'Card Image Height', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_image_height',
				'default'     => '232px',
				'placeholder' => 'e.g., 232px, 15rem',
				'label_for'   => 'card_image_height',
			)
		);

		add_settings_field(
			'card_content_height',
			__( 'Card Content Height', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_content_height',
				'default'     => '200px',
				'placeholder' => 'e.g., 200px, auto',
				'label_for'   => 'card_content_height',
			)
		);

		// Card Spacing.
		add_settings_field(
			'card_content_padding_top',
			__( 'Card Content Padding Top', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_content_padding_top',
				'default'     => '24px',
				'placeholder' => 'e.g., 24px, 1.5rem',
				'label_for'   => 'card_content_padding_top',
			)
		);

		add_settings_field(
			'card_content_padding_sides',
			__( 'Card Content Padding Sides', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_content_padding_sides',
				'default'     => '10px',
				'placeholder' => 'e.g., 10px, 0.5rem',
				'label_for'   => 'card_content_padding_sides',
			)
		);

		// Grid Settings.
		add_settings_field(
			'card_grid_min_width',
			__( 'Grid Item Minimum Width', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_grid_min_width',
				'default'     => '280px',
				'placeholder' => 'e.g., 280px, 18rem',
				'description' => __( 'Minimum width for each card in the grid.', 'dynamic-online-services' ),
				'label_for'   => 'card_grid_min_width',
			)
		);

		add_settings_field(
			'card_grid_column_gap',
			__( 'Grid Column Gap', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_grid_column_gap',
				'default'     => '20px',
				'placeholder' => 'e.g., 20px, 1.25rem',
				'label_for'   => 'card_grid_column_gap',
			)
		);

		add_settings_field(
			'card_grid_row_gap',
			__( 'Grid Row Gap', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_grid_row_gap',
				'default'     => '40px',
				'placeholder' => 'e.g., 40px, 2.5rem',
				'label_for'   => 'card_grid_row_gap',
			)
		);

		// Card Effects.
		add_settings_field(
			'card_hover_transform',
			__( 'Card Hover Transform', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_hover_transform',
				'default'     => 'translateY(-5px)',
				'placeholder' => 'e.g., translateY(-5px), scale(1.05)',
				'description' => __( 'CSS transform on hover. Leave empty to disable.', 'dynamic-online-services' ),
				'label_for'   => 'card_hover_transform',
			)
		);

		add_settings_field(
			'card_hover_transition',
			__( 'Card Hover Transition Speed', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_hover_transition',
				'default'     => '0.2s',
				'placeholder' => 'e.g., 0.2s, 300ms',
				'label_for'   => 'card_hover_transition',
			)
		);

		add_settings_field(
			'card_box_shadow',
			__( 'Card Box Shadow', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_cards_section',
			array(
				'name'        => 'card_box_shadow',
				'default'     => '0 4px 6px rgba(0, 0, 0, 0.1)',
				'placeholder' => 'e.g., 0 4px 6px rgba(0, 0, 0, 0.1)',
				'description' => __( 'CSS box-shadow value. Leave empty to disable.', 'dynamic-online-services' ),
				'label_for'   => 'card_box_shadow',
			)
		);
	}
}
