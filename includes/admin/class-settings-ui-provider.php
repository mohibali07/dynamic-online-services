<?php
/**
 * Settings UI Provider Class
 *
 * Responsible for registering settings sections and fields for the admin UI.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

use TechmireSolutions\DynamicOnlineServices\Settings\Config;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * SettingsUiProvider class.
 *
 * Single responsibility: Register settings sections and fields for admin UI.
 *
 * @since 1.1.3
 */
class SettingsUiProvider
{

	/**
	 * Register admin UI settings (sections and fields).
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function register(): void
	{
		$config_map = Config::get_map();

		foreach ($config_map as $section_key => $section_data) {
			// Register section
			add_settings_section(
				$section_data['id'],
				$section_data['title'],
				'__return_null',
				'Dynamic_Online_Services'
			);

			// Register fields for this section
			if (isset($section_data['fields']) && is_array($section_data['fields'])) {
				self::register_section_fields($section_data);
			}
		}
	}

	/**
	 * Register fields for a settings section.
	 *
	 * @since 1.1.3
	 * @param array $section_data Section configuration data.
	 * @return void
	 */
	private static function register_section_fields(array $section_data): void
	{
		foreach ($section_data['fields'] as $field_id => $field_data) {
			$args = [
				'name' => $field_id,
				'default' => $field_data['default'] ?? '',
				'label_for' => $field_id,
			];

			// Merge any additional args from config
			if (isset($field_data['args']) && is_array($field_data['args'])) {
				$args = array_merge($args, $field_data['args']);
			}

			add_settings_field(
				$field_id,
				$field_data['title'],
				$field_data['callback'],
				'Dynamic_Online_Services',
				$section_data['id'],
				$args
			);
		}
	}
}
