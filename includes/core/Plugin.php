<?php
/**
 * Main Plugin Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization;
use TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices;
use WP_Post;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin class.
 */
class Plugin
{



	/**
	 * Container instance.
	 *
	 * @var Container
	 */
	private $container;

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
		require_once DYNOS_PLUGIN_DIR . 'includes/core/Container.php';
		$this->container = new Container();

		$this->load_dependencies();
		$this->define_hooks();
	}

	/**
	 * Load dependencies.
	 */
	private function load_dependencies(): void
	{
		require_once DYNOS_PLUGIN_DIR . 'includes/functions-helpers.php';

		// Required for CPT settings sanitization and validation used in constructors.
		require_once DYNOS_PLUGIN_DIR . 'cpt/Sanitization.php';

		// Load Internationalization.
		require_once DYNOS_PLUGIN_DIR . 'includes/core/functions-i18n.php';

		// Required for permalink structure (filter hook).
		require_once DYNOS_PLUGIN_DIR . 'cpt/functions-permalink.php';

		// Initialize Taxonomy Fields.
		\TechmireSolutions\DynamicOnlineServices\Taxonomies\Taxonomy\FieldsRenderer::init();
		\TechmireSolutions\DynamicOnlineServices\Taxonomies\Taxonomy\FieldsSaver::init();

		// Register and Init Assets
		$this->container->register(FrontendAssets::class, new FrontendAssets());
		$this->container->get(FrontendAssets::class)->init();

		$this->container->register(\TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets::class, new \TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets());
		$this->container->get(\TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets::class)->init();

		// Initialize Post Types and Taxonomies
		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug = $settings['service_slug'];
		$taxonomy_slug = $settings['taxonomy_slug'];

		$this->container->register(\TechmireSolutions\DynamicOnlineServices\Cpt\ServicePostType::class, new \TechmireSolutions\DynamicOnlineServices\Cpt\ServicePostType($service_slug));
		$this->container->get(\TechmireSolutions\DynamicOnlineServices\Cpt\ServicePostType::class)->register();

		$this->container->register(\TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy::class, new \TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy($taxonomy_slug, array($service_slug)));
		$this->container->get(\TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy::class)->register();

		// Register Service Keywords Taxonomy
		$this->container->register(\TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceKeywordsTaxonomy::class, new \TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceKeywordsTaxonomy(array($service_slug)));
		$this->container->get(\TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceKeywordsTaxonomy::class)->register();

		// Note: faqs-meta-box.php is still required if it wasn't refactored into a class yet.
		// We marked it as "check if procedural". It is procedural.
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/MetaBox.php';
		require_once DYNOS_PLUGIN_DIR . 'includes/faqs/Saver.php';
		\TechmireSolutions\DynamicOnlineServices\FAQs\MetaBox::init();
		\TechmireSolutions\DynamicOnlineServices\FAQs\Saver::init();
		\TechmireSolutions\DynamicOnlineServices\FAQs\AdminScripts::init();

		// Initialize Settings.
		// Singleton, but we can still register it if we wanted to access it via container,
		// though consistent usage suggests calling ::get_instance() inside a closure if needed.
		// For now, we leave the singleton call as is to not break internal patterns too much.
		\TechmireSolutions\DynamicOnlineServices\Admin\Settings::get_instance();

		// Initialize Shortcodes.
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Category::init();
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::init();
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::init();
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\MarketingWidgetShortcode::init();

		// Initialize Hero Cache.
		\TechmireSolutions\DynamicOnlineServices\Cache\HeroCache::init();

		// New Cards Shortcode.
		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards::init();

		// Initialize Integrations.
		\TechmireSolutions\DynamicOnlineServices\Integration\LiteSpeedCache::init();

		// Initialize Renderers.
		$this->container->register(\TechmireSolutions\DynamicOnlineServices\Renderers\WhatsappRenderer::class, new \TechmireSolutions\DynamicOnlineServices\Renderers\WhatsappRenderer());
		$this->container->get(\TechmireSolutions\DynamicOnlineServices\Renderers\WhatsappRenderer::class)->init();

		// Initialize API.
		\TechmireSolutions\DynamicOnlineServices\Api\MarketingController::init();
	}

	/**
	 * Define hooks.
	 */
	private function define_hooks(): void
	{
		// Activation/Deactivation hooks are handled in main file.

		// Admin notices.
		add_action('admin_notices', array($this, 'display_activation_error_notice'));

		// Register Blocks
		add_action('init', array($this, 'register_blocks'));

		// LCP Optimization
		add_action('wp_head', array($this, 'preload_hero_image'), 1);
	}

	/**
	 * Register custom blocks.
	 *
	 * @return void
	 */
	public function register_blocks(): void
	{
		register_block_type(DYNOS_PLUGIN_DIR . 'build/blocks/service-cards');
		register_block_type(DYNOS_PLUGIN_DIR . 'build/blocks/hero-section');
	}

	/**
	 * Preload Hero Image for LCP.
	 */
	public function preload_hero_image(): void
	{
		if (!is_singular()) {
			return;
		}

		$post = get_post();
		if (!$post instanceof WP_Post || !has_block('dynamic-online-services/hero-section', $post)) {
			return;
		}

		$blocks = parse_blocks($post->post_content);
		foreach ($blocks as $block) {
			if ('dynamic-online-services/hero-section' === $block['blockName']) {
				$attrs = $block['attrs'];
				$img_url = isset($attrs['desktopImageUrl']) ? $attrs['desktopImageUrl'] : '';

				// Mobile check (simple server-side check).
				if (wp_is_mobile() && !empty($attrs['mobileImageUrl'])) {
					$img_url = $attrs['mobileImageUrl'];
				}

				if (!empty($img_url)) {
					printf(
						'<link rel="preload" as="image" href="%s" fetchpriority="high">',
						esc_url($img_url)
					);
				}
				break; // Only preload the first hero found.
			}
		}
	}

	/**
	 * Display activation error notice.
	 */
	public function display_activation_error_notice(): void
	{
		$error_message = get_transient('dynos_activation_error');
		if ($error_message) {
			delete_transient('dynos_activation_error');
			if (class_exists(AdminNotices::class)) {
				AdminNotices::error((string) $error_message);
			} else {
				printf(
					'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
					wp_kses_post($error_message)
				);
			}
		}
	}
}
