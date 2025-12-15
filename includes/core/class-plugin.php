<?php
/**
 * Main Plugin Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin class.
 */
class Plugin
{


	/**
	 * Instance.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Get instance.
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin
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
		$this->load_dependencies();
		$this->define_hooks();
	}

	/**
	 * Load dependencies.
	 */
	private function load_dependencies(): void
	{
		require_once DYNOS_PLUGIN_DIR . 'includes/helpers.php';
		// require_once DYNOS_PLUGIN_DIR . 'includes/post-types.php'; // Legacy loader replaced

		// Required for CPT settings sanitization and validation used in constructors
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/class-sanitization.php';

		// Load Internationalization
		require_once DYNOS_PLUGIN_DIR . 'includes/core/i18n.php';

		// Required for permalink structure (filter hook)
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/permalink.php';

		require_once DYNOS_PLUGIN_DIR . 'includes/taxonomy-fields.php';

		// Initialize Assets
		$frontend_assets = new \TechmireSolutions\DynamicOnlineServices\Core\FrontendAssets();
		$frontend_assets->init();

		$admin_assets = new \TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets();
		$admin_assets->init();

		// Initialize Post Types and Taxonomies
		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug = $settings['service_slug'];
		$taxonomy_slug = $settings['taxonomy_slug'];

		$service_cpt = new \TechmireSolutions\DynamicOnlineServices\PostTypes\ServicePostType($service_slug);
		$service_cpt->register();

		$service_tax = new \TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy($taxonomy_slug, array($service_slug));
		$service_tax->register();

		// Note: faqs-meta-box.php is still required if it wasn't refactored into a class yet.
		// We marked it as "check if procedural". It is procedural.
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/class-meta-box.php';
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/class-saver.php';
		\TechmireSolutions\DynamicOnlineServices\FAQs\MetaBox::init();
		\TechmireSolutions\DynamicOnlineServices\FAQs\Saver::init();

		// Initialize Settings
		\TechmireSolutions\DynamicOnlineServices\Admin\Settings::get_instance();

		// Initialize Shortcodes
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Category::init();
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::init();
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::init();

		// New Cards Shortcode
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards::init();

		// Initialize Integrations
		\TechmireSolutions\DynamicOnlineServices\Integration\LiteSpeedCache::init();
	}

	/**
	 * Define hooks.
	 */
	private function define_hooks(): void
	{
		// Activation/Deactivation hooks are handled in main file

		// Admin notices
		add_action('admin_notices', array($this, 'display_activation_error_notice'));

		// Register Blocks
		add_action('init', array($this, 'register_blocks'));
	}

	/**
	 * Register blocks.
	 */
	public function register_blocks(): void
	{
		register_block_type(DYNOS_PLUGIN_DIR . 'build/blocks/service-cards');
	}

	/**
	 * Display activation error notice.
	 */
	public function display_activation_error_notice(): void
	{
		$error_message = get_transient('dynos_activation_error');
		if ($error_message) {
			delete_transient('dynos_activation_error');
			if (class_exists('\TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices')) {
				\TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices::error($error_message);
			} else {
				printf(
					'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
					wp_kses_post($error_message)
				);
			}
		}
	}
}
