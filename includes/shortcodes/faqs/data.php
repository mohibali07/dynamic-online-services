<?php
/**
 * FAQs Shortcode Data Retrieval
 *
 * Handles data retrieval for FAQ shortcode.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get FAQs for a service post.
 *
 * @since 1.1.0
 * @param int $post_id Post ID.
 * @return array Array of FAQs or empty array.
 */
function dynos_get_service_faqs( $post_id ): array {
	// Validate post ID
	$post_id = dynos_validate_post_id( $post_id );
	if ( false === $post_id ) {
		return [];
	}

	$faqs = get_post_meta( $post_id, 'service_faqs', true );

	if ( ! is_array( $faqs ) || empty( $faqs ) ) {
		return [];
	}

	// Sanitize FAQ data to ensure security
	$sanitized_faqs = [];
	foreach ( $faqs as $faq ) {
		if ( ! is_array( $faq ) ) {
			continue;
		}

		$question = isset( $faq['question'] ) ? sanitize_text_field( $faq['question'] ) : '';
		$answer   = isset( $faq['answer'] ) ? wp_kses_post( $faq['answer'] ) : '';

		// Only include FAQs with valid questions
		if ( ! empty( $question ) ) {
			$sanitized_faqs[] = [
				'question' => $question,
				'answer'   => $answer,
			];
		}
	}

	// Allow filtering FAQs before return
	$sanitized_faqs = apply_filters( 'dynos_faqs_before_display', $sanitized_faqs, $post_id );

	return $sanitized_faqs;
}
