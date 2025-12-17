<?php
/**
 * Admin Assets Class
 *
 * Handles enqueuing of admin scripts and styles.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

use TechmireSolutions\DynamicOnlineServices\Helpers\Screen;
use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization;
use TechmireSolutions\DynamicOnlineServices\Settings\Config;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Admin assets class.
 */
class AdminAssets
{

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.2.0
	 */
	public function init(): void
	{
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.2.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_scripts(string $hook_suffix): void
	{
		$this->enqueue_settings_scripts($hook_suffix);
		$this->enqueue_taxonomy_scripts($hook_suffix);
		$this->enqueue_post_edit_scripts($hook_suffix);
	}

	/**
	 * Enqueue scripts for the settings page.
	 *
	 * @since 1.2.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	private function enqueue_settings_scripts(string $hook_suffix): void
	{
		if ('settings_page_dynamic-online-services' !== $hook_suffix) {
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

		$script_path = DYNOS_PLUGIN_DIR . 'build/index.js';
		$script_asset_path = DYNOS_PLUGIN_DIR . 'build/index.asset.php';
		$script_url = DYNOS_PLUGIN_URL . 'build/index.js';

		if (file_exists($script_asset_path)) {
			$script_asset = require $script_asset_path;

			wp_enqueue_script(
				'dynos-settings-app',
				$script_url,
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);

			// Localize script for initial data using the correct handle
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
		wp_localize_script(
			'dynos-settings-app',
			'dynosSettings',
			[
				'apiUrl'         => esc_url_raw(rest_url('dynamic-online-services/v1/')),
				'nonce'          => wp_create_nonce('wp_rest'),
				'maxGridColumns' => isset($options['max_grid_columns']) ? absint($options['max_grid_columns']) : 6,
				'minGridColumns' => isset($options['min_grid_columns']) ? absint($options['min_grid_columns']) : 1,
				'fields'         => Config::get_map(),
				'colorPresets'   => apply_filters(
					'dynos_color_picker_presets',
					[
						[ 'name' => __('Black', 'dynamic-online-services'), 'color' => '#000000' ],
						[ 'name' => __('White', 'dynamic-online-services'), 'color' => '#ffffff' ],
						[ 'name' => __('Red', 'dynamic-online-services'), 'color' => '#f00' ],
						[ 'name' => __('Blue', 'dynamic-online-services'), 'color' => '#00f' ],
					]
				),
			]
		);

			// Enqueue styles
			// wp-components stylesheet is required for the components to look right
			wp_enqueue_style('wp-components', false, [], DYNOS_VERSION);

			// Enqueue Custom Admin Settings Style
			if (file_exists(DYNOS_PLUGIN_DIR . 'assets/css/admin-style.css')) {
				wp_enqueue_style(
					'dynos-admin-settings-style',
					DYNOS_PLUGIN_URL . 'assets/css/admin-style.css',
					[],
					DYNOS_VERSION
				);
			}

			if (file_exists(DYNOS_PLUGIN_DIR . 'build/index.css')) {
				wp_enqueue_style(
					'dynos-settings-app',
					DYNOS_PLUGIN_URL . 'build/index.css',
					['wp-components'],
					$script_asset['version']
				);
			}
		}
	}

	/**
	 * Enqueue scripts for the taxonomy pages.
	 *
	 * @since 1.2.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	private function enqueue_taxonomy_scripts(string $hook_suffix): void
	{
		// Check if we're on the taxonomy add or edit page
		$settings = Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

		$is_add_form = Screen::is_taxonomy_add_screen($taxonomy_slug);
		$is_edit_form = Screen::is_taxonomy_edit_screen($taxonomy_slug);
		// Check if on category edit screen
		$is_edit_screen = Screen::is_current_screen('edit-tags', '', $taxonomy_slug);

		if (!$is_add_form && !$is_edit_form && !$is_edit_screen) {
			return;
		}

		// Check user capabilities
		if (!current_user_can('manage_categories')) {
			return;
		}

		wp_enqueue_media();

		// Enqueue common admin JavaScript first (for shared functions)
		wp_enqueue_script(
			'dynos-admin-common',
			DYNOS_PLUGIN_URL . 'assets/js/admin-common.js',
			['jquery'],
			DYNOS_VERSION,
			true
		);

		wp_enqueue_script(
			'dynos-taxonomy-media-uploader',
			DYNOS_PLUGIN_URL . 'assets/js/taxonomy-media-uploader.js',
			['jquery', 'dynos-admin-common'],
			DYNOS_VERSION,
			true
		);

		// Localize script for translations
		wp_localize_script(
			'dynos-taxonomy-media-uploader',
			'dynosTaxonomyMedia',
			[
				'title' => __('Choose Thumbnail', 'dynamic-online-services'),
				'button' => __('Choose Image', 'dynamic-online-services'),
			]
		);
	}

	/**
	 * Enqueue scripts for the post edit screen (Meta Boxes).
	 *
	 * @since 1.2.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	private function enqueue_post_edit_scripts(string $hook_suffix): void
	{
		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug = isset($settings['service_slug']) ? $settings['service_slug'] : 'service';

		if (!Screen::is_post_edit_screen($service_slug)) {
			return;
		}

		// Enqueue Custom Admin Styles
		if (file_exists(DYNOS_PLUGIN_DIR . 'assets/css/admin-style.css')) {
			// Reuse the same stylesheet, assuming we will add meta box styles there
			wp_enqueue_style(
				'dynos-admin-style',
				DYNOS_PLUGIN_URL . 'assets/css/admin-style.css',
				[],
				DYNOS_VERSION
			);
		}
	}
}
