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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include settings registration files
// Include settings registration files
require_once DYNOS_PLUGIN_DIR . 'includes/settings/sections/class-hero-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/sections/class-cards-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/sections/class-faq-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/sections/class-post-type-settings.php';

/**
 * Initialize plugin settings.
 *
 * @since 1.1.0
 */
function dynos_settings_init(): void {
	// Register setting with sanitization callback
	register_setting(
		'Dynamic_Online_Services',
		'dynos_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => array( '\TechmireSolutions\DynamicOnlineServices\Settings\Sanitization', 'sanitize' ),
			'default'           => \TechmireSolutions\DynamicOnlineServices\Settings\Defaults::get_options(),
		)
	);

	// Hero Section Settings
	add_settings_section(
		'dynos_hero_section',
		__( 'Hero Section Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	\TechmireSolutions\DynamicOnlineServices\Settings\Sections\HeroSettings::register();

	// Service Cards Settings
	add_settings_section(
		'dynos_cards_section',
		_x( 'Service Cards Settings', 'Settings section title', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	\TechmireSolutions\DynamicOnlineServices\Settings\Sections\CardsSettings::register();

	// FAQ Accordion Settings
	add_settings_section(
		'dynos_faq_section',
		__( 'FAQ Accordion Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	\TechmireSolutions\DynamicOnlineServices\Settings\Sections\FaqSettings::register();

	// Post Type & Taxonomy Settings
	add_settings_section(
		'dynos_post_type_section',
		__( 'Post Type & Taxonomy Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	\TechmireSolutions\DynamicOnlineServices\Settings\Sections\PostTypeSettings::register();
}
add_action( 'admin_init', 'dynos_settings_init' );
