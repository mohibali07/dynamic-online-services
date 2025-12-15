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
require_once DYNOS_PLUGIN_DIR . 'includes/settings/hero-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/cards-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/faq-settings.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/post-type-settings.php';

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
			'sanitize_callback' => 'dynos_sanitize_options',
			'default'           => dynos_get_default_options(),
		)
	);

	// Hero Section Settings
	add_settings_section(
		'dynos_hero_section',
		__( 'Hero Section Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	dynos_register_hero_settings();

	// Service Cards Settings
	add_settings_section(
		'dynos_cards_section',
		_x( 'Service Cards Settings', 'Settings section title', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	dynos_register_cards_settings();

	// FAQ Accordion Settings
	add_settings_section(
		'dynos_faq_section',
		__( 'FAQ Accordion Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	dynos_register_faq_settings();

	// Post Type & Taxonomy Settings
	add_settings_section(
		'dynos_post_type_section',
		__( 'Post Type & Taxonomy Settings', 'dynamic-online-services' ),
		'__return_null',
		'Dynamic_Online_Services'
	);

	dynos_register_post_type_settings();
}
add_action( 'admin_init', 'dynos_settings_init' );
