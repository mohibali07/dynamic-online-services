<?php
/**
 * Admin Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Admin;

use DynamicOnlineServices\Settings\PageRenderer;
use DynamicOnlineServices\Settings\Defaults;
use DynamicOnlineServices\Settings\Sanitization;

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
		add_action('admin_init', array($this, 'register_settings'));
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
	 * Register settings.
	 */
	public function register_settings(): void
	{
		// Register setting with sanitization callback
		register_setting(
			'Dynamic_Online_Services',
			'dynos_options',
			array(
				'type' => 'array',
				'sanitize_callback' => array(Sanitization::class, 'sanitize'),
				'default' => Defaults::get_options(),
			)
		);

		// Post Type & Taxonomy Settings
		add_settings_section(
			'dynos_post_type_section',
			__('Post Type & Taxonomy Settings', 'dynamic-online-services'),
			'__return_null',
			'Dynamic_Online_Services'
		);

		\DynamicOnlineServices\Settings\Sections\PostTypeSettings::register();

		// Hero Section Settings
		add_settings_section(
			'dynos_hero_section',
			__('Hero Section Settings', 'dynamic-online-services'),
			'__return_null',
			'Dynamic_Online_Services'
		);

		\DynamicOnlineServices\Settings\Sections\HeroSettings::register();

		// Service Cards Settings
		add_settings_section(
			'dynos_cards_section',
			_x('Service Cards Settings', 'Settings section title', 'dynamic-online-services'),
			'__return_null',
			'Dynamic_Online_Services'
		);

		\DynamicOnlineServices\Settings\Sections\CardsSettings::register();

		// FAQ Accordion Settings
		add_settings_section(
			'dynos_faq_section',
			__('FAQ Accordion Settings', 'dynamic-online-services'),
			'__return_null',
			'Dynamic_Online_Services'
		);

		\DynamicOnlineServices\Settings\Sections\FaqSettings::register();
	}
}
