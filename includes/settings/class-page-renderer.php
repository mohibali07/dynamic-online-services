<?php
/**
 * Settings Page Renderer Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * PageRenderer class.
 */
class PageRenderer
{



	/**
	 * Render settings page HTML.
	 *
	 * @return void
	 */
	public static function render(): void
	{
		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'dynamic-online-services'));
		}

		?>
		<div class="wrap">
			<div id="dynos-settings-root"></div>
		</div>
		<?php
	}

	/**
	 * Render flush rewrite rules section.
	 *
	 * @return void
	 */
	public static function render_flush_rewrite_section(): void
	{
		?>
		<div class="dynos-settings-section">
			<p class="description">
				<?php esc_html_e('If you change the URL slugs above, you may need to flush your rewrite rules to prevent 404 errors.', 'dynamic-online-services'); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Render uninstall settings section.
	 *
	 * @return void
	 */
	public static function render_uninstall_settings(): void
	{
		?>
		<div class="dynos-settings-section">
			<fieldset>
				<label for="dynos_delete_data_on_uninstall">
					<input type="checkbox" id="dynos_delete_data_on_uninstall" name="dynos_options[delete_data_on_uninstall]"
						value="1" <?php checked(1, \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get_option(\TechmireSolutions\DynamicOnlineServices\Helpers\Options::get(), 'delete_data_on_uninstall', 0)); ?>>
					<?php esc_html_e('Delete all plugin data on uninstall', 'dynamic-online-services'); ?>
				</label>
				<p class="description">
					<?php esc_html_e('Check this box if you want to remove all services, categories, and settings when deleting the plugin.', 'dynamic-online-services'); ?>
				</p>
			</fieldset>
		</div>
		<?php
	}
}
