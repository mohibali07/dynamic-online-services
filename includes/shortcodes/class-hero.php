<?php
/**
 * Hero Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Shortcodes;

use TechmireSolutions\DynamicOnlineServices\Helpers\ShortcodeAttributes;
use TechmireSolutions\DynamicOnlineServices\Renderers\HeroRenderer;
use TechmireSolutions\DynamicOnlineServices\Services\SettingsService;
use WP_Post;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Hero class.
 */
class Hero
{


	/**
	 * Initialize shortcodes.
	 */
	public static function init(): void
	{
		add_shortcode('service_category_hero', [__CLASS__, 'render_category_hero']);
		add_shortcode('single_service_hero', [__CLASS__, 'render_service_hero']);
	}

	/**
	 * Render category hero shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_category_hero($atts = []): string
	{
		// Require data helper for retrieval functions
		if (!function_exists('dynos_get_category_hero_data')) {
			require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/hero/data.php';
		}

		$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

		if (!is_tax($taxonomy_slug)) {
			return '';
		}

		$queried_object = get_queried_object();
		if (function_exists('dynos_validate_term_object')) {
			$queried_object = dynos_validate_term_object($queried_object, $taxonomy_slug);
		}

		if (!$queried_object) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				do_action('dynos_category_hero_term_error', get_queried_object());
			}
			return '';
		}

		// Allow filtering the queried object
		$queried_object = apply_filters('dynos_category_hero_term', $queried_object);

		// Get hero data
		$hero_data = dynos_get_category_hero_data($queried_object);

		// Process and Render
		$output = self::process_hero($atts, $hero_data, 'service_category_hero');

		// Allow filtering the final output
		return apply_filters('dynos_category_hero_output', $output, $queried_object);
	}

	/**
	 * Render service hero shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_service_hero($atts = []): string
	{
		// Require data helper
		if (!function_exists('dynos_get_service_hero_data')) {
			require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/hero/data.php';
		}

		$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
		$service_slug = isset($settings['service_slug']) ? $settings['service_slug'] : 'service';

		if (!is_singular($service_slug)) {
			return '';
		}

		// Get post object
		$post = get_queried_object();
		if (!$post || !($post instanceof WP_Post)) {
			$post = get_post();
		}

		// Validate post object
		if (function_exists('dynos_validate_post_object')) {
			$post = dynos_validate_post_object($post, $service_slug);
		}

		if (!$post) {
			return '';
		}

		// Get hero data
		$hero_data = dynos_get_service_hero_data($post);

		// Process and Render
		$output = self::process_hero($atts, $hero_data, 'single_service_hero');

		// Allow filtering the final output
		return apply_filters('dynos_service_hero_output', $output, $post);
	}

	/**
	 * Process hero logic: Parse attributes, enqueue styles, then render.
	 * Replaces legacy dynos_process_hero_shortcode.
	 *
	 * @param array  $atts
	 * @param array  $hero_data
	 * @param string $shortcode_tag
	 * @return string
	 */
	private static function process_hero($atts, $hero_data, $shortcode_tag): string
	{
		// Get default height from settings via Service
		$settings_service = SettingsService::get_instance();
		$default_height = $settings_service->get_option('hero_height', '50vh');

		// Parse shortcode attributes using consolidated helper class
		$atts = ShortcodeAttributes::parse(
			(array) $atts,
			['height' => $default_height],
			$shortcode_tag
		);

		// Validate height
		$atts['height'] = ShortcodeAttributes::validate_hero_height($atts['height'], $default_height);

		// Override height if provided
		if (!empty($atts['height'])) {
			$hero_data['height'] = $atts['height'];
		}

		// Breadcrumbs integration
		$enable_breadcrumbs = $settings_service->get_option('rank_math_breadcrumbs', true);
		if ($enable_breadcrumbs && function_exists('rank_math_the_breadcrumbs')) {
			// Rank Math outputs directly, so we capture it
			ob_start();
			rank_math_the_breadcrumbs();
			$hero_data['breadcrumbs'] = ob_get_clean();
		} else {
			$hero_data['breadcrumbs'] = '';
		}

		// Enqueue styles
		\TechmireSolutions\DynamicOnlineServices\Styles\HeroStyles::enqueue($hero_data['font_family'], $hero_data['overlay_color']);

		// Render using Renderer Class
		$renderer = new HeroRenderer();
		return $renderer->render($hero_data);
	}
}
