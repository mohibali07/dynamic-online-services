<?php
/**
 * Admin Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

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
		add_action('admin_menu', [SettingsMenuProvider::class, 'register']);
		add_action('init', [SettingsApiProvider::class, 'register']);
		add_action('admin_init', [SettingsUiProvider::class, 'register']);
	}

	/**
	 * Add admin menu.
	 *
	 * @deprecated 1.1.3 Use SettingsMenuProvider::register() instead.
	 */
	public function add_menu(): void
	{
		SettingsMenuProvider::register();
	}

	/**
	 * Register settings for REST API.
	 *
	 * @since 1.2.0
	 * @deprecated 1.1.3 Use SettingsApiProvider::register() instead.
	 */
	public function register_api_settings(): void
	{
		SettingsApiProvider::register();
	}

	/**
	 * Register admin UI settings (sections and fields).
	 *
	 * @since 1.2.0
	 * @deprecated 1.1.3 Use SettingsUiProvider::register() instead.
	 */
	public function register_admin_ui(): void
	{
		SettingsUiProvider::register();
	}
}
