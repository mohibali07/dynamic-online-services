<?php
/**
 * Settings Defaults Class
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
 * Defaults class.
 */
class Defaults
{


	/**
	 * Get default options array.
	 *
	 * @return array Default option values.
	 */
	/**
	 * Get default options array.
	 *
	 * @return array Default option values.
	 */
	public static function get_options(): array
	{
		$defaults = [];
		$map      = Config::get_map();

		foreach ($map as $section) {
			if (isset($section['fields']) && is_array($section['fields'])) {
				foreach ($section['fields'] as $field_id => $field_data) {
					if (isset($field_data['default'])) {
						$defaults[$field_id] = $field_data['default'];
					}
				}
			}
		}

		return $defaults;
	}
}
