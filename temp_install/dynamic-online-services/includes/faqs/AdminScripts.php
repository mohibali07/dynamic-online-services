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

namespace TechmireSolutions\DynamicOnlineServices\FAQs;

use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization;
use TechmireSolutions\DynamicOnlineServices\Helpers\Screen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Scripts Class
 */
class AdminScripts {

	/**
	 * Initialize the scripts.
	 */
	public static function init(): void {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Enqueue FAQ admin scripts.
	 *
	 * @since 1.1.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public static function enqueue( $hook_suffix ): void {
		// Only load on service post edit screens
		$settings     = Sanitization::sanitize_cpt_settings();
		$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

		if ( ! Screen::is_post_edit_screen( $service_slug ) ) {
			return;
		}

		// Check user capabilities
		if ( ! current_user_can( 'edit_posts' ) ) {
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
}
