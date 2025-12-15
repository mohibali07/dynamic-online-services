<?php
/**
 * Settings Field Renderers Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Settings;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * FieldRenderers class.
 */
class FieldRenderers
{




	/**
	 * Render color picker field callback.
	 *
	 * @param array $args Field arguments.
	 */
	public static function color_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];

		printf(
			'<input type="text" class="doc-color-picker" name="dynos_options[%1$s]" id="%1$s" value="%2$s" />',
			esc_attr($args['name']),
			esc_attr($value)
		);

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render font family field callback.
	 *
	 * @param array $args Field arguments.
	 */
	public static function font_family_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];

		printf(
			'<input type="text" name="dynos_options[%1$s]" id="%1$s" value="%2$s" class="regular-text" />',
			esc_attr($args['name']),
			esc_attr($value)
		);

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render number field callback.
	 *
	 * @param array $args Field arguments.
	 */
	public static function number_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];
		$min = isset($args['min']) ? $args['min'] : '';
		$max = isset($args['max']) ? $args['max'] : '';
		$step = isset($args['step']) ? $args['step'] : '0.1';

		// Build min/max attributes with proper escaping.
		echo '<input type="number" name="dynos_options[' . esc_attr($args['name']) . ']" id="' . esc_attr($args['name']) . '" value="' . esc_attr($value) . '" class="small-text" step="' . esc_attr($step) . '"';

		if (!empty($min)) {
			echo ' min="' . esc_attr($min) . '"';
		}

		if (!empty($max)) {
			echo ' max="' . esc_attr($max) . '"';
		}

		echo ' />';

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render text field callback for CSS dimensions.
	 *
	 * @param array $args Field arguments.
	 */
	public static function text_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];

		printf(
			'<input type="text" name="dynos_options[%1$s]" id="%1$s" value="%2$s" class="regular-text" placeholder="%3$s" />',
			esc_attr($args['name']),
			esc_attr($value),
			isset($args['placeholder']) ? esc_attr($args['placeholder']) : ''
		);

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}
}
