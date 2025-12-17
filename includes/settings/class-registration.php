<?php
/**
 * Settings Registration
 *
 * Handles registration of plugin settings, sections, and fields.
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
 * Class Registration
 */
class Registration {

	/**
	 * Initialize settings registration.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function init(): void {
		\add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	/**
	 * Register settings.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function register_settings(): void {
		// Register setting with sanitization callback
		\register_setting(
			'Dynamic_Online_Services',
			'dynos_options',
			[
				'type'              => 'array',
				'sanitize_callback' => [ '\TechmireSolutions\DynamicOnlineServices\Settings\Sanitization', 'sanitize' ],
				'default'           => \TechmireSolutions\DynamicOnlineServices\Settings\Defaults::get_options(),
			]
		);

        // Get map from Config
        $map = Config::get_map();

        foreach ($map as $key => $section) {
            // Register Section
            \add_settings_section(
                $section['id'],
                $section['title'],
                '__return_null', // Section callback (description)
                'Dynamic_Online_Services'
            );

            // Register Fields
            Section::register($key);
        }
	}
}
