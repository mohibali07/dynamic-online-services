<?php
/**
 * Plugin Uninstaller
 *
 * Handles plugin uninstall tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Core;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.1.0
 * @package    Dynamic_Online_Services
 * @subpackage Core
 * @author     Techmire Solutions <http://techmiresolutions.com/>
 */
class Uninstaller
{

	/**
	 * Uninstall the plugin.
	 *
	 * Fired when the plugin is uninstalled.
	 *
	 * @since    1.1.0
	 */
	public static function uninstall(): void
	{
		// Check if user has permission to uninstall.
		if (!current_user_can('activate_plugins')) {
			return;
		}

		// Check if we should delete data on uninstall.
		$delete_data = get_option('dynos_delete_data_on_uninstall', false);

		if ($delete_data) {
			// Delete plugin options.
			delete_option('dynos_options');
			delete_option('ss_options'); // Clean up old option name.
			delete_option('dynos_delete_data_on_uninstall');

			// Note: We don't delete custom post types and taxonomies data
			// as this could be destructive. Users should manually delete if needed.
		}

		// Clear rewrite rules.
		flush_rewrite_rules();
	}
}
