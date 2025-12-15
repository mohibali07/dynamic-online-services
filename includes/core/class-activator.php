<?php
/**
 * Plugin Activator
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\Settings\Defaults;
use TechmireSolutions\DynamicOnlineServices\PostTypes\ServicePostType;
use TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Activator
 */
class Activator {

	/**
	 * Activate the plugin.
	 *
	 * @return void
	 */
	public static function activate(): void {
		try {
			// Load error handler if not already loaded
			if ( ! function_exists( 'dynos_handle_activation_error' ) ) {
				$plugin_dir = defined( 'DYNOS_PLUGIN_DIR' ) ? DYNOS_PLUGIN_DIR : plugin_dir_path( dirname( __DIR__, 2 ) );
				if ( file_exists( $plugin_dir . 'includes/core/class-plugin-exception.php' ) ) {
					require_once $plugin_dir . 'includes/core/class-plugin-exception.php';
				}
				if ( file_exists( $plugin_dir . 'includes/core/class-error-handler.php' ) ) {
					require_once $plugin_dir . 'includes/core/class-error-handler.php';
				}
			}

			// Check requirements
			if ( function_exists( 'dynos_check_requirements' ) && ! dynos_check_requirements() ) {
				deactivate_plugins( defined( 'DYNOS_PLUGIN_BASENAME' ) ? DYNOS_PLUGIN_BASENAME : plugin_basename( dirname( __DIR__, 2 ) . '/dynamic-online-services.php' ) );
				set_transient( 'dynos_activation_error', __( 'Dynamic Online Services could not be activated. Please check the system requirements.', 'dynamic-online-services' ), 30 );
				return;
			}

			self::migrate_options();
			self::initialize_slugs();
			self::register_post_types_and_flush();

			// Set activation flag
			set_transient( 'dynos_plugin_activated', true, 30 );
		} catch (\Exception $e) {
			// Log the error
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log('DYNOS Activation Failed: ' . $e->getMessage());

			// Set error transient
			set_transient(
				'dynos_activation_error',
				sprintf(
					/* translators: %s: error message */
					__('Dynamic Online Services activation failed: %s', 'dynamic-online-services'),
					$e->getMessage()
				),
				30
			);

			// Deactivate to prevent broken state
			deactivate_plugins(
				defined('DYNOS_PLUGIN_BASENAME') ? DYNOS_PLUGIN_BASENAME : plugin_basename(dirname(__DIR__, 2) . '/dynamic-online-services.php')
			);
		}
	}

	/**
	 * Migrate old options.
	 *
	 * @return void
	 */
	private static function migrate_options(): void {
		$old_ss_options = get_option( 'ss_options', false );
		if ( false !== $old_ss_options && false === get_option( 'dynos_options', false ) ) {
			update_option( 'dynos_options', $old_ss_options );
		}

		$old_sos_options = get_option( 'sos_options', false );
		if ( false !== $old_sos_options && false === get_option( 'dynos_options', false ) ) {
			update_option( 'dynos_options', $old_sos_options );
		}
	}

	/**
	 * Initialize slug options.
	 *
	 * @return void
	 */
	private static function initialize_slugs(): void {
		$current_options = get_option( 'dynos_options', array() );

		// Defaults class might need to be checked if it exists/autoloader works
		// Assumes DynamicOnlineServices\Settings\Defaults exists based on original code usage
		$defaults      = Defaults::get_options();
		$service_slug  = $defaults['service_post_type_slug'];
		$taxonomy_slug = $defaults['service_taxonomy_slug'];

		if ( ! isset( $current_options['service_post_type_slug'] ) || empty( $current_options['service_post_type_slug'] ) ) {
			update_option( 'dynos_previous_service_slug', $service_slug );
			update_option( 'dynos_previous_taxonomy_slug', $taxonomy_slug );
		} else {
			update_option( 'dynos_previous_service_slug', $current_options['service_post_type_slug'] );
			update_option( 'dynos_previous_taxonomy_slug', $current_options['service_taxonomy_slug'] );
		}
	}

	/**
	 * Register post types and flush rewrite rules.
	 *
	 * @return void
	 */
	private static function register_post_types_and_flush(): void {
		// Get settings to know slugs
		$settings = get_option( 'dynos_options', array() );
		$defaults = Defaults::get_options(); // Assuming this works

		$service_slug  = $settings['service_post_type_slug'] ?? $defaults['service_post_type_slug'];
		$taxonomy_slug = $settings['service_taxonomy_slug'] ?? $defaults['service_taxonomy_slug'];

		// Instantiate registration classes
		// Note: we can't easily rely on the Plugin class instance here during static activation
		// so we instantiate them manually just for flushing rules.

		$cpt = new ServicePostType( $service_slug );
		$cpt->register_post_type();

		$tax = new ServiceCategoryTaxonomy( $taxonomy_slug, array( $service_slug ) );
		$tax->register_taxonomy();

		flush_rewrite_rules();
	}
}
