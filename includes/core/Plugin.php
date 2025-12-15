<?php
/**
 * Main Plugin Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Core;

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
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/sanitization.php';

		// Required for permalink structure (filter hook)
		require_once DYNOS_PLUGIN_DIR . 'includes/post-types/permalink.php';

		require_once DYNOS_PLUGIN_DIR . 'includes/taxonomy-fields.php';
		require_once DYNOS_PLUGIN_DIR . 'includes/front-end-scripts.php';
		require_once DYNOS_PLUGIN_DIR . 'includes/admin-scripts.php';

		// Initialize Post Types and Taxonomies
		$settings = dynos_sanitize_cpt_settings();
		$service_slug = $settings['service_slug'];
		$taxonomy_slug = $settings['taxonomy_slug'];

		$service_cpt = new \DynamicOnlineServices\PostTypes\ServicePostType($service_slug);
		$service_cpt->register();

		$service_tax = new \DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy($taxonomy_slug, array($service_slug));
		$service_tax->register();

		// Note: faqs-meta-box.php is still required if it wasn't refactored into a class yet.
		// We marked it as "check if procedural". It is procedural.
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs-meta-box.php';

		// Initialize Settings
		\DynamicOnlineServices\Admin\Settings::get_instance();

		// Initialize Shortcodes
		\DynamicOnlineServices\Shortcodes\Category::init();
		\DynamicOnlineServices\Shortcodes\Faqs::init();
		\DynamicOnlineServices\Shortcodes\Hero::init();

		// New Cards Shortcode
		// Note: Cards.php is likely not autoloader compliant if it was manually required before.
		// Let's check if we need to require it. It is in includes/shortcodes/Cards.php
		// namespace DynamicOnlineServices\Shortcodes; class Cards
		// The autoloader usually maps namespaces.
		// Ideally we should remove this require if autoloader works, but to be safe and strictly follow the plan of *removing* legacy:
		// We will keep Cards require if it was new, but previous legacy wrappers are gone.
		require_once DYNOS_PLUGIN_DIR . 'includes/Shortcodes/Cards.php';
		\DynamicOnlineServices\Shortcodes\Cards::init();

		// Initialize Integrations
		\DynamicOnlineServices\Integration\LiteSpeedCache::init();
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
			if (function_exists('dynos_admin_error_notice')) {
				dynos_admin_error_notice($error_message);
			} else {
				printf(
					'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
					wp_kses_post($error_message)
				);
			}
		}
	}
}
