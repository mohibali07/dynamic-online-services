<?php
/**
 * FAQs Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Shortcodes;

use TechmireSolutions\DynamicOnlineServices\Helpers\ShortcodeAttributes;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Faqs class.
 */
class Faqs {


	/**
	 * Initialize shortcode.
	 */
	public static function init(): void {
		add_shortcode( 'service_faqs_accordion', [ __CLASS__, 'render' ] );
	}

	/**
	 * Render shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render( $atts = [] ): string {
		// Include dependencies
		if ( ! function_exists( 'dynos_render_faqs_accordion' ) ) {
			require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/faqs/renderer.php';
		}
		if ( ! function_exists( 'dynos_get_service_faqs' ) ) {
			require_once DYNOS_PLUGIN_DIR . 'includes/shortcodes/faqs/data.php';
		}

		// Parse and validate shortcode attributes
		$default_title = __( 'Frequently Asked Questions', 'dynamic-online-services' );

		$atts = ShortcodeAttributes::parse(
			(array) $atts,
			[ 'title' => $default_title ],
			'service_faqs_accordion'
		);

		// Validate title attribute
		$atts['title'] = ShortcodeAttributes::validate_faq_title( $atts['title'], $default_title );

		$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
		$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

		// Check if we're on a service post
		if ( ! is_singular( $service_slug ) ) {
			// Provide helpful message for admins/editors
			if ( \current_user_can( 'edit_posts' ) ) {
				return sprintf(
					'<div class="dynos-shortcode-notice" style="padding:10px;background:#fff3cd;border-left:4px solid #ffc107;margin:10px 0;">
						<strong>%s:</strong> %s
					</div>',
					esc_html__( 'FAQs Shortcode Notice', 'dynamic-online-services' ),
					esc_html__( 'This shortcode only works on individual service pages. It will not display on archive or category pages.', 'dynamic-online-services' )
				);
			}
			return '';
		}

		// Get post object
		$post = get_queried_object();
		if ( ! $post || ! ( $post instanceof WP_Post ) ) {
			$post = get_post();
		}

		// Validate post object
		if ( function_exists( 'dynos_validate_post_object' ) ) {
			$post = dynos_validate_post_object( $post, $service_slug );
		}

		if ( ! $post ) {
			return '';
		}

		$post_id = $post->ID;

		// Get FAQs
		$faqs = dynos_get_service_faqs( $post_id );

		if ( empty( $faqs ) ) {
			$empty_content = apply_filters( 'dynos_faqs_empty_content', '', $post_id );
			return $empty_content;
		}

		// Render FAQ accordion
		$output = dynos_render_faqs_accordion( $faqs, $post_id, $atts['title'] );

		// Allow filtering the final output
		return apply_filters( 'dynos_faqs_output', $output, $faqs, $post_id );
	}
}
