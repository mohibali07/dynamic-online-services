<?php
/**
 * Hero Section Configuration
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
 * Hero configuration class.
 */
class Hero {

	/**
	 * Get hero section settings map.
	 *
	 * @return array Settings map.
	 */
	public static function get_map(): array {
		return array(
			'id'     => 'dynos_hero_section',
			'title'  => __( 'Hero Section Settings', 'dynamic-online-services' ),
			'fields' => array(
				'hero_title_color'                  => array(
					'title'    => __( 'Hero Title Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#FFFFFF',
					'args'     => array(
						'description' => __( 'Color of the main hero title.', 'dynamic-online-services' ),
					),
				),
				'hero_overlay_color'                => array(
					'title'    => __( 'Hero Overlay Color', 'dynamic-online-services' ),
					'callback' => 'dynos_color_field_callback',
					'default'  => '#000000',
					'args'     => array(
						'description' => __( 'This color will be semi-transparent.', 'dynamic-online-services' ),
					),
				),
				'hero_overlay_opacity'              => array(
					'title'    => __( 'Hero Overlay Opacity', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '0.5',
					'args'     => array(
						'min'         => '0',
						'max'         => '1',
						'step'        => '0.1',
						'description' => __( 'Overlay opacity from 0 (transparent) to 1 (opaque).', 'dynamic-online-services' ),
					),
				),
				'hero_font_family'                  => array(
					'title'    => __( 'Hero Font Family', 'dynamic-online-services' ),
					'callback' => 'dynos_font_family_field_callback',
					'default'  => 'sans-serif',
					'args'     => array(
						'description' => __( 'You can use a standard font or a Google Font name.', 'dynamic-online-services' ),
					),
				),
				'hero_height'                       => array(
					'title'    => __( 'Hero Height (Desktop)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '50vh',
					'args'     => array(
						'placeholder' => 'e.g., 50vh, 400px',
						'description' => __( 'Hero section height for desktop. Use px, vh, or %.', 'dynamic-online-services' ),
					),
				),
				'hero_height_tablet'                => array(
					'title'    => __( 'Hero Height (Tablet)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '30vh',
					'args'     => array(
						'placeholder' => 'e.g., 30vh, 300px',
						'description' => __( 'Hero section height for tablets (max-width: 768px).', 'dynamic-online-services' ),
					),
				),
				'hero_height_mobile'                => array(
					'title'    => __( 'Hero Height (Mobile)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '30vh',
					'args'     => array(
						'placeholder' => 'e.g., 30vh, 250px',
						'description' => __( 'Hero section height for mobile devices (max-width: 480px).', 'dynamic-online-services' ),
					),
				),
				'hero_title_font_size'              => array(
					'title'    => __( 'Hero Title Font Size (Desktop)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '3rem',
					'args'     => array(
						'placeholder' => 'e.g., 3rem, 48px',
						'description' => __( 'Font size for the hero title on desktop.', 'dynamic-online-services' ),
					),
				),
				'hero_title_font_size_tablet'       => array(
					'title'    => __( 'Hero Title Font Size (Tablet)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '2.5rem',
					'args'     => array(
						'placeholder' => 'e.g., 2.5rem, 40px',
						'description' => __( 'Font size for the hero title on tablets.', 'dynamic-online-services' ),
					),
				),
				'hero_title_font_size_mobile'       => array(
					'title'    => __( 'Hero Title Font Size (Mobile)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '2rem',
					'args'     => array(
						'placeholder' => 'e.g., 2rem, 32px',
						'description' => __( 'Font size for the hero title on mobile.', 'dynamic-online-services' ),
					),
				),
				'hero_description_font_size'        => array(
					'title'    => __( 'Hero Description Font Size (Desktop)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '1.25rem',
					'args'     => array(
						'placeholder' => 'e.g., 1.25rem, 20px',
						'description' => __( 'Font size for the hero description on desktop.', 'dynamic-online-services' ),
					),
				),
				'hero_description_font_size_tablet' => array(
					'title'    => __( 'Hero Description Font Size (Tablet)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '1rem',
					'args'     => array(
						'placeholder' => 'e.g., 1rem, 16px',
						'description' => __( 'Font size for the hero description on tablets.', 'dynamic-online-services' ),
					),
				),
				'hero_description_font_size_mobile' => array(
					'title'    => __( 'Hero Description Font Size (Mobile)', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '0.9rem',
					'args'     => array(
						'placeholder' => 'e.g., 0.9rem, 14px',
						'description' => __( 'Font size for the hero description on mobile.', 'dynamic-online-services' ),
					),
				),
				'hero_content_padding'              => array(
					'title'    => __( 'Hero Content Padding', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '20px',
					'args'     => array(
						'placeholder' => 'e.g., 20px, 1rem 2rem',
						'description' => __( 'Padding inside the hero container.', 'dynamic-online-services' ),
					),
				),
				'hero_border_radius'                => array(
					'title'    => __( 'Hero Content Border Radius', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '10px',
					'args'     => array(
						'placeholder' => 'e.g., 10px, 0.5rem',
						'description' => __( 'Rounding of the hero content box corners.', 'dynamic-online-services' ),
					),
				),
				'hero_margin_bottom'                => array(
					'title'    => __( 'Hero Margin Bottom', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '30px',
					'args'     => array(
						'placeholder' => 'e.g., 30px, 2rem',
						'description' => __( 'Space below the hero section.', 'dynamic-online-services' ),
					),
				),
				'rank_math_breadcrumbs'             => array(
					'title'    => __( 'Enable Rank Math Breadcrumbs', 'dynamic-online-services' ),
					'callback' => 'dynos_checkbox_field_callback',
					'default'  => true,
					'args'     => array(
						'label'       => __( 'Show Breadcrumbs in Hero Section', 'dynamic-online-services' ),
						'description' => __( 'Requires Rank Math SEO plugin to be active.', 'dynamic-online-services' ),
					),
				),
			),
		);
	}
}
