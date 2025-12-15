<?php
/**
 * Taxonomy Admin Scripts
 *
 * Handles enqueuing of admin scripts for taxonomy pages.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts for the media uploader on taxonomy add/edit forms.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function dynos_enqueue_service_category_admin_scripts( $hook_suffix ): void {
	// Check if we're on the taxonomy add or edit page
	$settings      = function_exists( 'dynos_sanitize_cpt_settings' ) ? dynos_sanitize_cpt_settings() : array();
	$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

	$is_add_form    = dynos_is_taxonomy_add_screen( $taxonomy_slug );
	$is_edit_form   = dynos_is_taxonomy_edit_screen( $taxonomy_slug );
	$is_edit_screen = dynos_is_current_screen( 'edit-tags', '', $taxonomy_slug );

	if ( ! $is_add_form && ! $is_edit_form && ! $is_edit_screen ) {
		return;
	}

	wp_enqueue_media();

	// Enqueue common admin JavaScript first (for shared functions)
	wp_enqueue_script(
		'sos-admin-common',
		DYNOS_PLUGIN_URL . 'assets/js/admin-common.js',
		array( 'jquery' ),
		DYNOS_VERSION,
		true
	);

	wp_enqueue_script(
		'sos-taxonomy-media-uploader',
		DYNOS_PLUGIN_URL . 'assets/js/taxonomy-media-uploader.js',
		array( 'jquery', 'sos-admin-common' ),
		DYNOS_VERSION,
		true
	);

	// Localize script for translations
	wp_localize_script(
		'sos-taxonomy-media-uploader',
		'sosTaxonomyMedia',
		array(
			'title'  => __( 'Choose Thumbnail', 'dynamic-online-services' ),
			'button' => __( 'Choose Image', 'dynamic-online-services' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'dynos_enqueue_service_category_admin_scripts' );
