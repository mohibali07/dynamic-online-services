<?php
/**
 * FAQ Settings Class
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
 * FaqSettings class.
 */
class FaqSettings {


	/**
	 * Register settings.
	 */
	public static function register(): void {
		// FAQ Colors.
		add_settings_field(
			'faq_item_border_color',
			__( 'FAQ Item Border Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'      => 'faq_item_border_color',
				'default'   => '#ddd',
				'label_for' => 'faq_item_border_color',
			)
		);

		add_settings_field(
			'faq_question_bg_color',
			__( 'FAQ Question Background Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'      => 'faq_question_bg_color',
				'default'   => '#f7f7f7',
				'label_for' => 'faq_question_bg_color',
			)
		);

		add_settings_field(
			'faq_question_bg_hover',
			__( 'FAQ Question Hover Background Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'      => 'faq_question_bg_hover',
				'default'   => '#eee',
				'label_for' => 'faq_question_bg_hover',
			)
		);

		add_settings_field(
			'faq_question_text_color',
			__( 'FAQ Question Text Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'      => 'faq_question_text_color',
				'default'   => '#333',
				'label_for' => 'faq_question_text_color',
			)
		);

		add_settings_field(
			'faq_answer_text_color',
			__( 'FAQ Answer Text Color', 'dynamic-online-services' ),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'      => 'faq_answer_text_color',
				'default'   => '#333',
				'label_for' => 'faq_answer_text_color',
			)
		);

		// FAQ Dimensions.
		add_settings_field(
			'faq_item_border_radius',
			__( 'FAQ Item Border Radius', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_item_border_radius',
				'default'     => '8px',
				'placeholder' => 'e.g., 8px, 0.5rem',
				'label_for'   => 'faq_item_border_radius',
			)
		);

		add_settings_field(
			'faq_item_margin_bottom',
			__( 'FAQ Item Margin Bottom', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_item_margin_bottom',
				'default'     => '15px',
				'placeholder' => 'e.g., 15px, 1rem',
				'label_for'   => 'faq_item_margin_bottom',
			)
		);

		add_settings_field(
			'faq_item_box_shadow',
			__( 'FAQ Item Box Shadow', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_item_box_shadow',
				'default'     => '0 2px 4px rgba(0, 0, 0, 0.05)',
				'placeholder' => 'e.g., 0 2px 4px rgba(0, 0, 0, 0.05)',
				'description' => __( 'CSS box-shadow value. Leave empty to disable.', 'dynamic-online-services' ),
				'label_for'   => 'faq_item_box_shadow',
			)
		);

		// FAQ Typography.
		add_settings_field(
			'faq_question_font_size',
			__( 'FAQ Question Font Size', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_question_font_size',
				'default'     => '1.15rem',
				'placeholder' => 'e.g., 1.15rem, 18px',
				'label_for'   => 'faq_question_font_size',
			)
		);

		add_settings_field(
			'faq_icon_font_size',
			__( 'FAQ Icon Font Size', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_icon_font_size',
				'default'     => '1.5rem',
				'placeholder' => 'e.g., 1.5rem, 24px',
				'label_for'   => 'faq_icon_font_size',
			)
		);

		// FAQ Spacing.
		add_settings_field(
			'faq_question_padding',
			__( 'FAQ Question Padding', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_question_padding',
				'default'     => '15px 20px',
				'placeholder' => 'e.g., 15px 20px, 1rem 1.25rem',
				'label_for'   => 'faq_question_padding',
			)
		);

		add_settings_field(
			'faq_answer_padding',
			__( 'FAQ Answer Padding', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_answer_padding',
				'default'     => '20px',
				'placeholder' => 'e.g., 20px, 1.25rem',
				'label_for'   => 'faq_answer_padding',
			)
		);

		add_settings_field(
			'faq_answer_max_height',
			__( 'FAQ Answer Max Height', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_answer_max_height',
				'default'     => '500px',
				'placeholder' => 'e.g., 500px, none',
				'label_for'   => 'faq_answer_max_height',
			)
		);

		add_settings_field(
			'faq_transition_speed',
			__( 'FAQ Transition Speed', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_faq_section',
			array(
				'name'        => 'faq_transition_speed',
				'default'     => '0.4s',
				'placeholder' => 'e.g., 0.4s, 400ms',
				'label_for'   => 'faq_transition_speed',
			)
		);
	}
}
