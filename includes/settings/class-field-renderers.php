<?php
/**
 * Settings Field Renderers Class
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
		$type = isset($args['type']) ? $args['type'] : 'text';

		printf(
			'<input type="%1$s" name="dynos_options[%2$s]" id="%2$s" value="%3$s" class="regular-text" placeholder="%4$s" />',
			esc_attr($type),
			esc_attr($args['name']),
			esc_attr($value),
			isset($args['placeholder']) ? esc_attr($args['placeholder']) : ''
		);

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render checkbox field callback.
	 *
	 * @param array $args Field arguments.
	 */
	public static function checkbox_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];
		$checked = !empty($value);

		printf(
			'<label><input type="checkbox" name="dynos_options[%1$s]" id="%1$s" value="1" %2$s /> %3$s</label>',
			esc_attr($args['name']),
			checked($checked, true, false),
			isset($args['label']) ? esc_html($args['label']) : ''
		);

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render radio field callback.
	 *
	 * @param array $args Field arguments with 'options' array.
	 */
	public static function radio_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$current_value = isset($options[$args['name']]) ? $options[$args['name']] : $args['default'];
		$radio_options = isset($args['options']) && is_array($args['options']) ? $args['options'] : array();

		if (empty($radio_options)) {
			echo '<p class="description">' . esc_html__('No options available.', 'dynamic-online-services') . '</p>';
			return;
		}

		echo '<fieldset>';
		foreach ($radio_options as $value => $label) {
			printf(
				'<label style="display: block; margin-bottom: 8px;"><input type="radio" name="dynos_options[%1$s]" value="%2$s" %3$s /> %4$s</label>',
				esc_attr($args['name']),
				esc_attr($value),
				checked($current_value, $value, false),
				esc_html($label)
			);
		}
		echo '</fieldset>';

		if (!empty($args['description'])) {
			echo '<p class="description">' . esc_html($args['description']) . '</p>';
		}
	}

	/**
	 * Render WhatsApp Agents repeater field callback.
	 *
	 * Note: The actual editing is handled by the React application.
	 * This callback provides a fallback/informational view in the classic settings UI.
	 *
	 * @since 1.2.0
	 * @param array $args Field arguments.
	 */
	public static function agents_repeater_field_callback($args): void
	{
		$options = get_option('dynos_options', Defaults::get_options());
		$agents = isset($options[$args['name']]) && is_array($options[$args['name']]) ? $options[$args['name']] : array();

		if (empty($agents)) {
			echo '<p class="description">' . esc_html__('No agents configured. Use the settings application to add agents.', 'dynamic-online-services') . '</p>';
			return;
		}

		echo '<div class="dynos-agents-summary" style="margin-top: 10px; max-width: 600px;">';
		echo '<table class="widefat fixed striped">';
		echo '<thead><tr>';
		echo '<th>' . esc_html__('Name', 'dynamic-online-services') . '</th>';
		echo '<th>' . esc_html__('Number', 'dynamic-online-services') . '</th>';
		echo '<th>' . esc_html__('Label', 'dynamic-online-services') . '</th>';
		echo '</tr></thead>';
		echo '<tbody>';

		foreach ($agents as $agent) {
			echo '<tr>';
			echo '<td>' . (isset($agent['name']) ? esc_html($agent['name']) : '') . '</td>';
			echo '<td>' . (isset($agent['number']) ? esc_html($agent['number']) : '') . '</td>';
			echo '<td>' . (isset($agent['label']) ? esc_html($agent['label']) : '') . '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';
		echo '<p class="description">' . esc_html__('Note: Agents are managed via the Dynamic Online Services settings interface.', 'dynamic-online-services') . '</p>';
		echo '</div>';
	}
}
