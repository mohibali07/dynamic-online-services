<?php
/**
 * Settings Sanitization Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Sanitization class.
 */
class Sanitization
{



	/**
	 * Sanitize options array.
	 *
	 * @param array $options Options to sanitize.
	 * @return array Sanitized options.
	 */
	public static function sanitize(array $options): array
	{
		if (!is_array($options)) {
			return Defaults::get_options();
		}

		error_log('Dynos Sanitization Incoming: ' . print_r($options, true));

		$sanitized = array();
		$defaults = Defaults::get_options();

		// Sanitize color fields using WordPress's built-in hex color sanitization
		$color_fields = array(
			'hero_title_color',
			'hero_overlay_color',
			'card_bg_color',
			'card_title_color',
			'card_description_color',
			'card_button_bg_color',
			'card_button_text_color',
			'card_button_hover_bg_color',
			'card_button_hover_text_color',
			'faq_item_border_color',
			'faq_question_bg_color',
			'faq_question_bg_hover',
			'faq_question_text_color',
			'faq_answer_text_color',
		);

		foreach ($color_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = sanitize_hex_color($options[$field]);
				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		// Sanitize font family fields
		$font_fields = array(
			'hero_font_family',
			'card_font_family',
			'service_cpt_singular_name',
			'service_cpt_plural_name',
			'service_tax_singular_name',
			'service_tax_plural_name',
		);

		foreach ($font_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = sanitize_text_field($options[$field]);
				if (empty($sanitized[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = $defaults[$field];
			}
		}

		// Sanitize numeric/opacity fields (0-1 range)
		$opacity_fields = array(
			'hero_overlay_opacity',
		);

		foreach ($opacity_fields as $field) {
			if (isset($options[$field])) {
				$value = floatval($options[$field]);
				$sanitized[$field] = min(max($value, 0), 1);
				if (!is_numeric($sanitized[$field]) || $sanitized[$field] < 0 || $sanitized[$field] > 1) {
					$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : 0.5;
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : 0.5;
			}
		}

		// Sanitize CSS dimension fields
		$dimension_fields = array(
			'hero_title_font_size',
			'hero_title_font_size_tablet',
			'hero_title_font_size_mobile',
			'hero_description_font_size',
			'hero_description_font_size_tablet',
			'hero_description_font_size_mobile',
			'hero_height',
			'hero_height_tablet',
			'hero_height_mobile',
			'hero_content_padding',
			'hero_border_radius',
			'hero_margin_bottom',
			'card_title_font_size',
			'card_description_font_size',
			'card_button_font_size',
			'card_border_radius',
			'card_height',
			'card_image_height',
			'card_content_height',
			'card_content_padding_top',
			'card_content_padding_sides',
			'card_grid_gap',
			'card_grid_column_gap',
			'card_grid_row_gap',
			'card_grid_min_width',
			'card_hover_transition',
			'faq_item_border_radius',
			'faq_item_margin_bottom',
			'faq_question_padding',
			'faq_question_font_size',
			'faq_icon_font_size',
			'faq_answer_padding',
			'faq_answer_max_height',
			'faq_transition_speed',
		);

		foreach ($dimension_fields as $field) {
			if (isset($options[$field])) {
				// Using global helper for now
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::css_dimension($options[$field]);

				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		// Sanitize transform fields
		$transform_fields = array(
			'card_hover_transform',
		);

		foreach ($transform_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::css_transform($options[$field]);

				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		// Sanitize box-shadow fields
		$box_shadow_fields = array(
			'card_box_shadow',
			'faq_item_box_shadow',
		);

		foreach ($box_shadow_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::css_box_shadow($options[$field]);

				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		// Sanitize slug fields
		$slug_fields = array(
			'service_post_type_slug' => 'services',
			'service_taxonomy_slug' => 'service-category',
		);

		foreach ($slug_fields as $field => $default_slug) {
			if (isset($options[$field])) {
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::validate_slug($options[$field], isset($defaults[$field]) ? $defaults[$field] : $default_slug);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : $default_slug;
			}
		}

		// Sanitize menu position
		if (isset($options['service_menu_position'])) {
			$menu_pos = trim($options['service_menu_position']);
			$sanitized['service_menu_position'] = empty($menu_pos) ? '' : absint($menu_pos);
		} else {
			$sanitized['service_menu_position'] = isset($defaults['service_menu_position']) ? $defaults['service_menu_position'] : '';
		}

		// Sanitize menu icon
		if (isset($options['service_menu_icon'])) {
			$sanitized['service_menu_icon'] = sanitize_text_field($options['service_menu_icon']);
			if (empty($sanitized['service_menu_icon']) && isset($defaults['service_menu_icon'])) {
				$sanitized['service_menu_icon'] = $defaults['service_menu_icon'];
			}
		} else {
			$sanitized['service_menu_icon'] = isset($defaults['service_menu_icon']) ? $defaults['service_menu_icon'] : 'dashicons-admin-customizer';
		}

		// Sanitize Google Font hosting method (whitelist validation)
		if (isset($options['google_font_hosting'])) {
			$valid_methods = array('remote', 'local');
			$sanitized['google_font_hosting'] = in_array($options['google_font_hosting'], $valid_methods, true)
				? $options['google_font_hosting']
				: 'remote';
		} else {
			$sanitized['google_font_hosting'] = isset($defaults['google_font_hosting']) ? $defaults['google_font_hosting'] : 'remote';
		}

		// Sanitize advanced numeric fields
		$numeric_fields = array(
			'max_grid_columns'       => array('min' => 1, 'max' => 12),
			'min_grid_columns'       => array('min' => 1, 'max' => 6),
			'max_taxonomy_depth'     => array('min' => 1, 'max' => 20),
			'default_excerpt_length' => array('min' => 5, 'max' => 100),
			'max_posts_per_page'     => array('min' => 10, 'max' => 5000),
		);

		foreach ($numeric_fields as $field => $constraints) {
			if (isset($options[$field])) {
				$value = intval($options[$field]);
				$sanitized[$field] = min(max($value, $constraints['min']), $constraints['max']);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : $constraints['min'];
			}
		}

		// Sanitize breakpoint fields
		$breakpoint_fields = array(
			'breakpoint_tablet',
			'breakpoint_mobile',
			'default_grid_min_width',
		);

		foreach ($breakpoint_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::css_dimension($options[$field]);
				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		// Sanitize Rank Math breadcrumbs checkbox
		if (isset($options['rank_math_breadcrumbs'])) {
			$sanitized['rank_math_breadcrumbs'] = !empty($options['rank_math_breadcrumbs']);
		} else {
			$sanitized['rank_math_breadcrumbs'] = isset($defaults['rank_math_breadcrumbs']) ? $defaults['rank_math_breadcrumbs'] : true;
		}

		// Sanitize WhatsApp Visibility
		if (isset($options['whatsapp_visibility'])) {
			$valid_visibility = array('all', 'home');
			$sanitized['whatsapp_visibility'] = in_array($options['whatsapp_visibility'], $valid_visibility, true)
				? $options['whatsapp_visibility']
				: 'all';
		} else {
			$sanitized['whatsapp_visibility'] = isset($defaults['whatsapp_visibility']) ? $defaults['whatsapp_visibility'] : 'all';
		}

		// Sanitize WhatsApp Availability
		if (isset($options['whatsapp_availability'])) {
			$sanitized['whatsapp_availability'] = !empty($options['whatsapp_availability']);
		} else {
			$sanitized['whatsapp_availability'] = isset($defaults['whatsapp_availability']) ? $defaults['whatsapp_availability'] : false;
		}

		// Sanitize WhatsApp Schedule
		foreach (['whatsapp_schedule_start', 'whatsapp_schedule_end'] as $field) {
			if (isset($options[$field])) {
				// Simple regex for HH:MM
				$sanitized[$field] = preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $options[$field])
					? $options[$field]
					: (isset($defaults[$field]) ? $defaults[$field] : '09:00');
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '09:00';
			}
		}

		// Sanitize WhatsApp Timezone
		if (isset($options['whatsapp_timezone'])) {
			// Validate if it's a valid timezone identifier
			$sanitized['whatsapp_timezone'] = in_array($options['whatsapp_timezone'], timezone_identifiers_list(), true)
				? $options['whatsapp_timezone']
				: 'UTC';
		} else {
			$sanitized['whatsapp_timezone'] = isset($defaults['whatsapp_timezone']) ? $defaults['whatsapp_timezone'] : 'UTC';
		}

		// Sanitize WhatsApp core fields (check existing keys to avoid overwrite if handled elsewhere?
		// Actually basic fields like enabled/number/messsage seem NOT explicitly handled in the loop above?
		// Wait, look at the file content...
		// "Sanitize color fields" handles some.
		// "Sanitize font family" handles some.
		// "Sanitize numeric" ...
		// CHECK lines 37-279 again. 'whatsapp_number', 'whatsapp_message', 'whatsapp_enabled' are MISSING in the big sanitization!
		// They might be getting lost too!
		// 'whatsapp_position' is also missing?
		// Let's check if they were in the Color/Dimension arrays.
		// whatsapp_bg_color -> Yes (color_fields)
		// whatsapp_enabled, whatsapp_number, whatsapp_message, whatsapp_position -> NO.
		// THIS IS LIKELY THE ROOT CAUSE for ALL WhatsApp settings being cleared, not just new ones.

		$text_fields = array('whatsapp_number', 'whatsapp_message');
		foreach ($text_fields as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = sanitize_text_field($options[$field]);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		if (isset($options['whatsapp_enabled'])) {
			$sanitized['whatsapp_enabled'] = !empty($options['whatsapp_enabled']);
		} else {
			$sanitized['whatsapp_enabled'] = isset($defaults['whatsapp_enabled']) ? $defaults['whatsapp_enabled'] : false;
		}

		if (isset($options['whatsapp_position'])) {
			$valid_api_pos = array('left', 'right');
			$sanitized['whatsapp_position'] = in_array($options['whatsapp_position'], $valid_api_pos, true) ? $options['whatsapp_position'] : 'right';
		} else {
			$sanitized['whatsapp_position'] = isset($defaults['whatsapp_position']) ? $defaults['whatsapp_position'] : 'right';
		}

		// Sanitize Offsets (CSS dimension)
		foreach (['whatsapp_position_offset_x', 'whatsapp_position_offset_y'] as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization::css_dimension($options[$field]);
				if (empty($sanitized[$field]) && isset($defaults[$field])) {
					$sanitized[$field] = $defaults[$field];
				}
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '20px';
			}
		}

		// Sanitize Icon Style
		if (isset($options['whatsapp_icon_style'])) {
			$valid_styles = array('default', 'chat', 'avatar');
			$sanitized['whatsapp_icon_style'] = in_array($options['whatsapp_icon_style'], $valid_styles, true)
				? $options['whatsapp_icon_style']
				: 'default';
		} else {
			$sanitized['whatsapp_icon_style'] = isset($defaults['whatsapp_icon_style']) ? $defaults['whatsapp_icon_style'] : 'default';
		}

		// Sanitize Toggles
		foreach (['whatsapp_show_desktop', 'whatsapp_show_mobile', 'whatsapp_analytics_enabled'] as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = !empty($options[$field]);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : false;
			}
		}

		// Sanitize CTA & Offline
		$text_fields_extra = array('whatsapp_offline_text', 'whatsapp_cta_text');
		foreach ($text_fields_extra as $field) {
			if (isset($options[$field])) {
				$sanitized[$field] = sanitize_text_field($options[$field]);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : '';
			}
		}

		if (isset($options['whatsapp_offline_behavior'])) {
			$valid_offline = array('hide', 'show');
			$sanitized['whatsapp_offline_behavior'] = in_array($options['whatsapp_offline_behavior'], $valid_offline, true) ? $options['whatsapp_offline_behavior'] : 'hide';
		} else {
			$sanitized['whatsapp_offline_behavior'] = isset($defaults['whatsapp_offline_behavior']) ? $defaults['whatsapp_offline_behavior'] : 'hide';
		}

		if (isset($options['whatsapp_cta_enabled'])) {
			$sanitized['whatsapp_cta_enabled'] = !empty($options['whatsapp_cta_enabled']);
		} else {
			$sanitized['whatsapp_cta_enabled'] = isset($defaults['whatsapp_cta_enabled']) ? $defaults['whatsapp_cta_enabled'] : false;
		}

		if (isset($options['whatsapp_cta_delay'])) {
			$sanitized['whatsapp_cta_delay'] = absint($options['whatsapp_cta_delay']);
		} else {
			$sanitized['whatsapp_cta_delay'] = isset($defaults['whatsapp_cta_delay']) ? $defaults['whatsapp_cta_delay'] : 5;
		}

		// Sanitize Multi-Agent Support.
		if ( isset( $options['whatsapp_agents_enabled'] ) ) {
			$sanitized['whatsapp_agents_enabled'] = ! empty( $options['whatsapp_agents_enabled'] );
		} else {
			$sanitized['whatsapp_agents_enabled'] = isset( $defaults['whatsapp_agents_enabled'] ) ? $defaults['whatsapp_agents_enabled'] : false;
		}

		if ( isset( $options['whatsapp_agents'] ) && is_array( $options['whatsapp_agents'] ) ) {
			$sanitized_agents = array();
			foreach ( $options['whatsapp_agents'] as $agent ) {
				if ( ! is_array( $agent ) ) {
					continue;
				}
				$sanitized_agents[] = array(
					'name'       => isset( $agent['name'] ) ? sanitize_text_field( $agent['name'] ) : '',
					'number'     => isset( $agent['number'] ) ? sanitize_text_field( $agent['number'] ) : '',
					'label'      => isset( $agent['label'] ) ? sanitize_text_field( $agent['label'] ) : '',
					'avatar_url' => isset( $agent['avatar_url'] ) ? esc_url_raw( $agent['avatar_url'] ) : '',
				);
			}
			$sanitized['whatsapp_agents'] = $sanitized_agents;
		} else {
			$sanitized['whatsapp_agents'] = isset( $defaults['whatsapp_agents'] ) ? $defaults['whatsapp_agents'] : array();
		}

		return wp_parse_args($sanitized, $defaults);
	}
}
