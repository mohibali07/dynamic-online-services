<?php
/**
 * Admin Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

use TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer;
use TechmireSolutions\DynamicOnlineServices\Settings\Defaults;
use TechmireSolutions\DynamicOnlineServices\Settings\Sanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Settings class.
 */
class Settings
{


	/**
	 * Instance.
	 *
	 * @var Settings
	 */
	private static $instance;

	/**
	 * Get instance.
	 *
	 * @return Settings
	 */
	public static function get_instance(): Settings
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct()
	{
		add_action('admin_menu', array($this, 'add_menu'));
		add_action('init', array($this, 'register_api_settings'));
		add_action('admin_init', array($this, 'register_admin_ui'));
	}

	/**
	 * Add admin menu.
	 */
	public function add_menu(): void
	{
		add_options_page(
			__('Dynamic Online Services Settings', 'dynamic-online-services'),
			__('Dynamic Services', 'dynamic-online-services'),
			'manage_options',
			'dynamic-online-services',
			array(PageRenderer::class, 'render')
		);
	}

	/**
	 * Register settings for REST API.
	 *
	 * @since 1.2.0
	 */
	public function register_api_settings(): void
	{
		// Register setting with sanitization callback
		register_setting(
			'Dynamic_Online_Services',
			'dynos_options',
			array(
				'type' => 'object',
				'sanitize_callback' => array(Sanitization::class, 'sanitize'),
				'default' => Defaults::get_options(),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							// We allow dynamic properties since it's a large options array
							// Ideal world: define every property here.
						),
						'additionalProperties' => true,
					),
				),
			)
		);
	}

	/**
	 * Register admin UI settings (sections and fields).
	 *
	 * @since 1.2.0
	 */
	public function register_admin_ui(): void
	{
		// Dynamically register sections and fields from Config
		$config_map = \TechmireSolutions\DynamicOnlineServices\Settings\Config::get_map();

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
				foreach ($section_data['fields'] as $field_id => $field_data) {
					$args = array(
						'name' => $field_id,
						'default' => $field_data['default'] ?? '',
						'label_for' => $field_id,
					);

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
	}
}
