<?php
/**
 * Hero Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Sections
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Settings\Sections;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * HeroSettings class.
 */
class HeroSettings
{


	/**
	 * Register settings.
	 */
	public static function register(): void
	{
		// Hero Colors.
		add_settings_field(
			'hero_title_color',
			__('Hero Title Color', 'dynamic-online-services'),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_title_color',
				'default' => '#FFFFFF',
				'label_for' => 'hero_title_color',
			)
		);

		add_settings_field(
			'hero_overlay_color',
			__('Hero Overlay Color', 'dynamic-online-services'),
			'dynos_color_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_overlay_color',
				'default' => '#000000',
				'description' => __('This color will be semi-transparent.', 'dynamic-online-services'),
				'label_for' => 'hero_overlay_color',
			)
		);

		add_settings_field(
			'hero_overlay_opacity',
			__('Hero Overlay Opacity', 'dynamic-online-services'),
			'dynos_number_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_overlay_opacity',
				'default' => '0.5',
				'min' => '0',
				'max' => '1',
				'step' => '0.1',
				'description' => __('Overlay opacity from 0 (transparent) to 1 (opaque).', 'dynamic-online-services'),
				'label_for' => 'hero_overlay_opacity',
			)
		);

		add_settings_field(
			'hero_font_family',
			__('Hero Font Family', 'dynamic-online-services'),
			'dynos_font_family_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_font_family',
				'default' => 'sans-serif',
				'description' => __('You can use a standard font or a Google Font name.', 'dynamic-online-services'),
				'label_for' => 'hero_font_family',
			)
		);

		// Hero Dimensions.
		add_settings_field(
			'hero_height',
			__('Hero Height (Desktop)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_height',
				'default' => '50vh',
				'placeholder' => 'e.g., 50vh, 400px',
				'description' => __('Hero section height for desktop. Use px, vh, or %.', 'dynamic-online-services'),
				'label_for' => 'hero_height',
			)
		);

		add_settings_field(
			'hero_height_tablet',
			__('Hero Height (Tablet)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_height_tablet',
				'default' => '30vh',
				'placeholder' => 'e.g., 30vh, 300px',
				'description' => __('Hero section height for tablets (max-width: 768px).', 'dynamic-online-services'),
				'label_for' => 'hero_height_tablet',
			)
		);

		add_settings_field(
			'hero_height_mobile',
			__('Hero Height (Mobile)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_height_mobile',
				'default' => '30vh',
				'placeholder' => 'e.g., 30vh, 250px',
				'description' => __('Hero section height for mobile devices (max-width: 480px).', 'dynamic-online-services'),
				'label_for' => 'hero_height_mobile',
			)
		);

		// Hero Typography.
		add_settings_field(
			'hero_title_font_size',
			__('Hero Title Font Size (Desktop)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_title_font_size',
				'default' => '3rem',
				'placeholder' => 'e.g., 3rem, 48px',
				'label_for' => 'hero_title_font_size',
			)
		);

		add_settings_field(
			'hero_title_font_size_tablet',
			__('Hero Title Font Size (Tablet)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_title_font_size_tablet',
				'default' => '2.5rem',
				'placeholder' => 'e.g., 2.5rem, 40px',
				'label_for' => 'hero_title_font_size_tablet',
			)
		);

		add_settings_field(
			'hero_title_font_size_mobile',
			__('Hero Title Font Size (Mobile)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_title_font_size_mobile',
				'default' => '2rem',
				'placeholder' => 'e.g., 2rem, 32px',
				'label_for' => 'hero_title_font_size_mobile',
			)
		);

		add_settings_field(
			'hero_description_font_size',
			__('Hero Description Font Size (Desktop)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_description_font_size',
				'default' => '1.25rem',
				'placeholder' => 'e.g., 1.25rem, 20px',
				'label_for' => 'hero_description_font_size',
			)
		);

		add_settings_field(
			'hero_description_font_size_tablet',
			__('Hero Description Font Size (Tablet)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_description_font_size_tablet',
				'default' => '1rem',
				'placeholder' => 'e.g., 1rem, 16px',
				'label_for' => 'hero_description_font_size_tablet',
			)
		);

		add_settings_field(
			'hero_description_font_size_mobile',
			__('Hero Description Font Size (Mobile)', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_description_font_size_mobile',
				'default' => '0.9rem',
				'placeholder' => 'e.g., 0.9rem, 14px',
				'label_for' => 'hero_description_font_size_mobile',
			)
		);

		// Hero Spacing.
		add_settings_field(
			'hero_content_padding',
			__('Hero Content Padding', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_content_padding',
				'default' => '20px',
				'placeholder' => 'e.g., 20px, 1rem 2rem',
				'label_for' => 'hero_content_padding',
			)
		);

		add_settings_field(
			'hero_border_radius',
			__('Hero Content Border Radius', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_border_radius',
				'default' => '10px',
				'placeholder' => 'e.g., 10px, 0.5rem',
				'label_for' => 'hero_border_radius',
			)
		);

		add_settings_field(
			'hero_margin_bottom',
			__('Hero Margin Bottom', 'dynamic-online-services'),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'hero_margin_bottom',
				'default' => '30px',
				'placeholder' => 'e.g., 30px, 2rem',
				'label_for' => 'hero_margin_bottom',
			)
		);

		// Breadcrumbs.
		add_settings_field(
			'rank_math_breadcrumbs',
			__('Enable Rank Math Breadcrumbs', 'dynamic-online-services'),
			'dynos_checkbox_field_callback',
			'Dynamic_Online_Services',
			'dynos_hero_section',
			array(
				'name' => 'rank_math_breadcrumbs',
				'label' => __('Show Breadcrumbs in Hero Section', 'dynamic-online-services'),
				'description' => __('Requires Rank Math SEO plugin to be active.', 'dynamic-online-services'),
				'label_for' => 'rank_math_breadcrumbs',
			)
		);
	}
}
