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

		$sanitized = [];
		$defaults = Defaults::get_options();

		// Sanitize color fields using WordPress's built-in hex color sanitization
		$color_fields = [
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
		];

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
		$font_fields = [
			'hero_font_family',
			'card_font_family',
			'service_cpt_singular_name',
			'service_cpt_plural_name',
			'service_tax_singular_name',
			'service_tax_plural_name',
		];

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
		$opacity_fields = [
			'hero_overlay_opacity',
		];

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
		$dimension_fields = [
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
		];

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
		$transform_fields = [
			'card_hover_transform',
		];

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
		$box_shadow_fields = [
			'card_box_shadow',
			'faq_item_box_shadow',
		];

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
		$slug_fields = [
			'service_post_type_slug' => 'services',
			'service_taxonomy_slug' => 'service-category',
		];

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
			$valid_methods = ['remote', 'local'];
			$sanitized['google_font_hosting'] = in_array($options['google_font_hosting'], $valid_methods, true)
				? $options['google_font_hosting']
				: 'remote';
		} else {
			$sanitized['google_font_hosting'] = isset($defaults['google_font_hosting']) ? $defaults['google_font_hosting'] : 'remote';
		}

		// Sanitize advanced numeric fields
		$numeric_fields = [
			'max_grid_columns'       => ['min' => 1, 'max' => 12],
			'min_grid_columns'       => ['min' => 1, 'max' => 6],
			'max_taxonomy_depth'     => ['min' => 1, 'max' => 20],
			'default_excerpt_length' => ['min' => 5, 'max' => 100],
			'max_posts_per_page'     => ['min' => 10, 'max' => 5000],
		];

		foreach ($numeric_fields as $field => $constraints) {
			if (isset($options[$field])) {
				$value = intval($options[$field]);
				$sanitized[$field] = min(max($value, $constraints['min']), $constraints['max']);
			} else {
				$sanitized[$field] = isset($defaults[$field]) ? $defaults[$field] : $constraints['min'];
			}
		}

		// Sanitize breakpoint fields
		$breakpoint_fields = [
			'breakpoint_tablet',
			'breakpoint_mobile',
			'default_grid_min_width',
		];

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

		return wp_parse_args($sanitized, $defaults);
	}
}
