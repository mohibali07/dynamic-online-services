<?php
/**
 * WhatsApp Configuration
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
 * WhatsApp configuration class.
 */
class Whatsapp {

	/**
	 * Get whatsapp settings map.
	 *
	 * @return array Settings map.
	 */
	public static function get_map(): array {
		return array(
			'id'     => 'dynos_whatsapp_section',
			'title'  => __( 'WhatsApp Settings', 'dynamic-online-services' ),
			'fields' => array(
				'whatsapp_enabled'           => array(
					'title'    => __( 'Enable WhatsApp Button', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
					'args'     => array(
						'label' => __( 'Show floating WhatsApp button on frontend.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_number'            => array(
					'title'    => __( 'WhatsApp Number', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '',
					'args'     => array(
						'type'        => 'tel',
						'placeholder' => 'e.g., 1234567890 (Include country code without +)',
						'description' => __( 'Enter phone number with country code. Do not include spaces or +.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_visibility'        => array(
					'title'    => __( 'Visibility', 'dynamic-online-services' ),
					'callback' => 'dynos_select_field_callback',
					'default'  => 'all',
					'args'     => array(
						'options'     => array(
							'all'  => __( 'All Pages', 'dynamic-online-services' ),
							'home' => __( 'Home Page Only', 'dynamic-online-services' ),
						),
						'description' => __( 'Where should the button appear?', 'dynamic-online-services' ),
					),
				),
				'whatsapp_availability'      => array(
					'title'    => __( 'Availability', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
					'args'     => array(
						'label'       => __( 'Enable Schedule', 'dynamic-online-services' ),
						'description' => __( 'If checked, the button will only show during the times below.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_schedule_start'    => array(
					'title'    => __( 'Start Time', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '09:00',
					'args'     => array(
						'type'        => 'time',
						'description' => __( 'Start time (24-hour format).', 'dynamic-online-services' ),
					),
				),
				'whatsapp_schedule_end'      => array(
					'title'    => __( 'End Time', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '17:00',
					'args'     => array(
						'type'        => 'time',
						'description' => __( 'End time (24-hour format).', 'dynamic-online-services' ),
					),
				),
				'whatsapp_timezone'          => array(
					'title'    => __( 'Timezone', 'dynamic-online-services' ),
					'callback' => 'dynos_select_field_callback',
					'default'  => 'UTC',
					'args'     => array(
						'options'     => array_reduce(
							\DateTimeZone::listIdentifiers(),
							function( $acc, $tz ) {
								$acc[ $tz ] = $tz;
								return $acc;
							},
							array()
						),
						'description' => __( 'Select your timezone.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_message'           => array(
					'title'    => __( 'Pre-filled Message', 'dynamic-online-services' ),
					'callback' => 'dynos_textarea_field_callback',
					'default'  => 'Hello, I have a question.',
					'args'     => array(
						'placeholder' => 'e.g., Hello, I need help.',
						'description' => __( 'Message to pre-fill when user clicks the button. Use {current_page_url} and {page_title} for dynamic values.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_position'          => array(
					'title'    => __( 'Button Position', 'dynamic-online-services' ),
					'callback' => 'dynos_select_field_callback',
					'default'  => 'right',
					'args'     => array(
						'options' => array(
							'right' => __( 'Bottom Right', 'dynamic-online-services' ),
							'left'  => __( 'Bottom Left', 'dynamic-online-services' ),
						),
					),
				),
				'whatsapp_bg_color'          => array(
					'title'    => __( 'Button Background Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#25D366',
					'args'     => array(
						'description' => __( 'Background color of the WhatsApp button.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_icon_color'        => array(
					'title'    => __( 'Button Icon Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#FFFFFF',
					'args'     => array(
						'description' => __( 'Color of the WhatsApp icon.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_position_offset_x' => array(
					'title'    => __( 'Horizontal Offset', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '20px',
					'args'     => array(
						'placeholder' => 'e.g., 20px',
						'description' => __( 'Distance from the left/right edge.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_position_offset_y' => array(
					'title'    => __( 'Vertical Offset', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '20px',
					'args'     => array(
						'placeholder' => 'e.g., 20px',
						'description' => __( 'Distance from the bottom edge.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_icon_style'        => array(
					'title'    => __( 'Icon Style', 'dynamic-online-services' ),
					'callback' => 'dynos_select_field_callback',
					'default'  => 'default',
					'args'     => array(
						'options'     => array(
							'default' => __( 'Official Logo', 'dynamic-online-services' ),
							'chat'    => __( 'Chat Bubble', 'dynamic-online-services' ),
							'avatar'  => __( 'User Avatar (Placeholder)', 'dynamic-online-services' ),
						),
						'description' => __( 'Choose the icon appearance.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_show_desktop'      => array(
					'title'    => __( 'Show on Desktop', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => true,
					'args'     => array(
						'label' => __( 'Display button on desktop devices.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_show_mobile'       => array(
					'title'    => __( 'Show on Mobile', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => true,
					'args'     => array(
						'label' => __( 'Display button on mobile devices.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_analytics_enabled' => array(
					'title'    => __( 'Enable Analytics', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
					'args'     => array(
						'label' => __( 'Fire GA/FB Pixel events on click.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_offline_behavior'  => array(
					'title'    => __( 'Offline Behavior', 'dynamic-online-services' ),
					'callback' => 'dynos_select_field_callback',
					'default'  => 'hide',
					'args'     => array(
						'options'     => array(
							'hide' => __( 'Hide Button', 'dynamic-online-services' ),
							'show' => __( 'Show Offline Message', 'dynamic-online-services' ),
						),
						'description' => __( 'What to do when outside schedule hours.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_offline_text'      => array(
					'title'    => __( 'Offline Message', 'dynamic-online-services' ),
					'callback' => 'dynos_textarea_field_callback',
					'default'  => __( 'We are currently offline. Please leave a message.', 'dynamic-online-services' ),
					'args'     => array(
						'description' => __( 'Message to show when offline.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_draggable'         => array(
					'title'    => __( 'Draggable Widget', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
					'args'     => array(
						'label'       => __( 'Allow users to drag the button on the screen.', 'dynamic-online-services' ),
						'description' => __( 'If enabled, visitors can move the WhatsApp button to their preferred location.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_cta_enabled'       => array(
					'title'    => __( 'Enable Call-to-Action', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
					'args'     => array(
						'label' => __( 'Show a CTA bubble next to the button.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_cta_text'          => array(
					'title'    => __( 'CTA Text', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => __( 'Need help? Chat with us!', 'dynamic-online-services' ),
					'args'     => array(
						'placeholder' => 'e.g., Need help?',
					),
				),
				'whatsapp_cta_delay'         => array(
					'title'    => __( 'CTA Delay (Seconds)', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '5',
					'args'     => array(
						'min'         => '0',
						'step'        => '1',
						'description' => __( 'Delay before showing CTA. 0 to show immediately.', 'dynamic-online-services' ),
					),
				),
				'whatsapp_agents_enabled'    => array(
					'title'    => __( 'Enable Multiple Agents', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => false,
				),
				'whatsapp_agents'            => array(
					'title'    => __( 'Agents List', 'dynamic-online-services' ),
					'callback' => 'dynos_agents_repeater_field_callback',
					'default'  => array(),
					'type'     => 'array',
				),
			),
		);
	}
}
