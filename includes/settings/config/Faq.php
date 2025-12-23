<?php
/**
 * FAQ Accordion Configuration
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
 * FAQ configuration class.
 */
class Faq {

	/**
	 * Get FAQ settings map.
	 *
	 * @return array Settings map.
	 */
	public static function get_map(): array {
		return array(
			'id'     => 'dynos_faq_section',
			'title'  => __( 'FAQ Accordion Settings', 'dynamic-online-services' ),
			'fields' => array(
				'faq_default_title'       => array(
					'title'    => __( 'Default FAQ Title', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => __( 'Frequently Asked Questions', 'dynamic-online-services' ),
					'args'     => array(
						'placeholder' => __( 'Frequently Asked Questions', 'dynamic-online-services' ),
						'description' => __( 'The default title to display above the FAQs if not specified in the shortcode.', 'dynamic-online-services' ),
					),
				),
				'faq_item_border_color'   => array(
					'title'    => __( 'FAQ Item Border Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#ddd',
					'args'     => array(
						'description' => __( 'Color of the border around each FAQ item.', 'dynamic-online-services' ),
					),
				),
				'faq_question_bg_color'   => array(
					'title'    => __( 'FAQ Question Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#f7f7f7',
					'args'     => array(
						'description' => __( 'Background color of the question area (collapsed state).', 'dynamic-online-services' ),
					),
				),
				'faq_question_bg_hover'   => array(
					'title'    => __( 'FAQ Question Hover Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#eee',
					'args'     => array(
						'description' => __( 'Background color when hovering over the question.', 'dynamic-online-services' ),
					),
				),
				'faq_question_text_color' => array(
					'title'    => __( 'FAQ Question Text Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#333',
					'args'     => array(
						'description' => __( 'Text color of the question.', 'dynamic-online-services' ),
					),
				),
				'faq_answer_text_color'   => array(
					'title'    => __( 'FAQ Answer Text Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#333',
					'args'     => array(
						'description' => __( 'Text color of the answer content.', 'dynamic-online-services' ),
					),
				),
				'faq_item_border_radius'  => array(
					'title'    => __( 'FAQ Item Border Radius', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '8px',
					'args'     => array(
						'placeholder' => 'e.g., 8px, 0.5rem',
						'description' => __( 'Rounding of the FAQ item corners.', 'dynamic-online-services' ),
					),
				),
				'faq_item_margin_bottom'  => array(
					'title'    => __( 'FAQ Item Margin Bottom', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '15px',
					'args'     => array(
						'placeholder' => 'e.g., 15px, 1rem',
						'description' => __( 'Space between FAQ items.', 'dynamic-online-services' ),
					),
				),
				'faq_item_box_shadow'     => array(
					'title'    => __( 'FAQ Item Box Shadow', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0 2px 4px rgba(0, 0, 0, 0.05)',
					'args'     => array(
						'placeholder' => 'e.g., 0 2px 4px rgba(0, 0, 0, 0.05)',
						'description' => __( 'CSS box-shadow value for each item. Leave empty to disable.', 'dynamic-online-services' ),
					),
				),
				'faq_question_font_size'  => array(
					'title'    => __( 'FAQ Question Font Size', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '1.15rem',
					'args'     => array(
						'placeholder' => 'e.g., 1.15rem, 18px',
						'description' => __( 'Font size for the question text.', 'dynamic-online-services' ),
					),
				),
				'faq_icon_font_size'      => array(
					'title'    => __( 'FAQ Icon Font Size', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '1.5rem',
					'args'     => array(
						'placeholder' => 'e.g., 1.5rem, 24px',
						'description' => __( 'Size of the toggle icon (+/-).', 'dynamic-online-services' ),
					),
				),
				'faq_question_padding'    => array(
					'title'    => __( 'FAQ Question Padding', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '15px 20px',
					'args'     => array(
						'placeholder' => 'e.g., 15px 20px, 1rem 1.25rem',
						'description' => __( 'Internal spacing for the question area.', 'dynamic-online-services' ),
					),
				),
				'faq_answer_padding'      => array(
					'title'    => __( 'FAQ Answer Padding', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '20px',
					'args'     => array(
						'placeholder' => 'e.g., 20px, 1.25rem',
						'description' => __( 'Internal spacing for the answer content.', 'dynamic-online-services' ),
					),
				),
				'faq_answer_max_height'   => array(
					'title'    => __( 'FAQ Answer Max Height', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '500px',
					'args'     => array(
						'placeholder' => 'e.g., 500px, none',
						'description' => __( 'Maximum height for the answer block. Used for the slide-down animation. Ensure it is large enough to fit your content.', 'dynamic-online-services' ),
					),
				),
				'faq_transition_speed'    => array(
					'title'    => __( 'FAQ Transition Speed', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0.4s',
					'args'     => array(
						'placeholder' => 'e.g., 0.4s, 400ms',
						'description' => __( 'Speed of the open/close animation.', 'dynamic-online-services' ),
					),
				),
			),
		);
	}
}
