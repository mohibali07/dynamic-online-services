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
		// Manually require sanitization class due to autoloader timing issues on frontend
		if ( ! class_exists( 'TechmireSolutions\\DynamicOnlineServices\\Settings\\Sanitization' ) ) {
			require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-sanitization.php';
		}

		add_action('admin_menu', array($this, 'add_menu'));

		// This registers the actual option and setting definition (Runs on Admin + REST)
		// This registers the actual option and setting definition (Runs on Admin + REST)
		add_action('init', array($this, 'register_settings'));

		// This adds the UI sections and fields (Runs on Admin ONLY)
		add_action('admin_init', array($this, 'add_settings_fields'));
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
	 * Register settings (Safe for REST API).
	 */
	public function register_settings(): void
	{
		// Ensure option exists in database before registering
		// This is critical for REST API exposure
		if (false === get_option('dynos_options')) {
			add_option('dynos_options', Defaults::get_options());
		}

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
						'type'       => 'object',
						'properties' => array(
							// We map specific properties to ensure they appear even if empty
							'max_grid_columns'  => array( 'type' => 'integer' ),
							'min_grid_columns'  => array( 'type' => 'integer' ),
							'whatsapp_enabled'  => array( 'type' => 'boolean' ),
							'whatsapp_number'   => array( 'type' => 'string' ),
							'whatsapp_message'  => array( 'type' => 'string' ),
							'whatsapp_position' => array( 'type' => 'string' ),
							'whatsapp_bg_color' => array( 'type' => 'string' ),
							'whatsapp_icon_color' => array( 'type' => 'string' ),
						),
						'additionalProperties' => true,
					),
				),
			)
		);
	}

	/**
	 * Add settings sections and fields (Admin UI only).
	 */
	public function add_settings_fields(): void
	{
		// Parse config and add sections/fields
		$config = \TechmireSolutions\DynamicOnlineServices\Settings\Config::get_map();

		foreach ($config as $section_key => $section_data) {
			add_settings_section(
				$section_data['id'],
				$section_data['title'],
				null,
				'Dynamic_Online_Services'
			);

			if (isset($section_data['fields']) && is_array($section_data['fields'])) {
				foreach ($section_data['fields'] as $field_id => $field_data) {
					$args = array(
						'label_for' => $field_id,
						'class' => $field_data['class'] ?? '',
					);

					// Pass field data to render callback
					$args = array_merge($args, $field_data);

					add_settings_field(
						$field_id,
						$field_data['title'],
						array($this, 'render_field'),
						'Dynamic_Online_Services',
						$section_data['id'],
						$args
					);
				}
			}
		}
	}

	/**
	 * Render field callback.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_field(array $args): void
	{
		$options = get_option('dynos_options');
		$field_id = $args['label_for'];
		$value = isset($options[$field_id]) ? $options[$field_id] : (isset($args['default']) ? $args['default'] : '');

		// Use the callback defined in config if available, or a default renderer
		if (isset($args['callback']) && is_callable($args['callback'])) {
			call_user_func($args['callback'], $args, $value);
		} elseif (isset($args['callback']) && is_string($args['callback']) && function_exists($args['callback'])) {
			call_user_func($args['callback'], $args, $value);
		} else {
             // Fallback renderer (simple text input)
             printf(
                 '<input type="text" id="%1$s" name="dynos_options[%1$s]" value="%2$s" class="regular-text" />',
                 esc_attr($field_id),
                 esc_attr($value)
             );
		}
	}
}
