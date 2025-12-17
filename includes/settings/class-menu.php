<?php
/**
 * Settings Menu
 *
 * Handles the main settings page menu registration.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Menu
 */
class Menu {

	/**
	 * Register the settings page menu.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function register(): void {
		\add_action( 'admin_menu', [ __CLASS__, 'add_admin_menu' ] );
	}

	/**
	 * Add admin menu.
	 *
	 * @since 1.1.0
	 */
	public static function add_admin_menu(): void {
		\add_options_page(
			__( 'Dynamic Online Services Settings', 'dynamic-online-services' ),
			__( 'Dynamic Services', 'dynamic-online-services' ),
			'manage_options',
			'dynamic-online-services',
			[ '\TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer', 'render' ]
		);
	}
}

