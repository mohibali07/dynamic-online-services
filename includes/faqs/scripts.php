<?php
/**
 * FAQs Admin Scripts
 *
 * Handles enqueuing of admin scripts for FAQ meta box.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue FAQ admin scripts.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function dynos_enqueue_faqs_admin_scripts( $hook_suffix ): void {
	// Only load on service post edit screens
	$settings     = function_exists( 'dynos_sanitize_cpt_settings' ) ? dynos_sanitize_cpt_settings() : array();
	$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

	if ( ! dynos_is_post_edit_screen( $service_slug ) ) {
		return;
	}

	// Enqueue common admin JavaScript first (for shared functions)
	wp_enqueue_script(
		'sos-admin-common',
		DYNOS_PLUGIN_URL . 'assets/js/admin-common.js',
		array( 'jquery' ),
		DYNOS_VERSION,
		true
	);

	wp_enqueue_script(
		'sos-faqs-admin',
		DYNOS_PLUGIN_URL . 'assets/js/faqs-admin.js',
		array( 'jquery', 'sos-admin-common' ),
		DYNOS_VERSION,
		true
	);

	// Localize script for translations
	wp_localize_script(
		'sos-faqs-admin',
		'sosFaqsAdmin',
		array(
			'questionLabel' => __( 'Question:', 'dynamic-online-services' ),
			'answerLabel'   => __( 'Answer:', 'dynamic-online-services' ),
			'removeLabel'   => __( 'Remove FAQ', 'dynamic-online-services' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'dynos_enqueue_faqs_admin_scripts' );
