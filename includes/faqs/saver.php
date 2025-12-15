<?php
/**
 * FAQs Saver
 *
 * Handles saving of FAQ data.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save the FAQs data.
 *
 * @since 1.1.0
 * @param int $post_id Post ID.
 * @return void
 */
function dynos_save_service_faqs_meta_box( $post_id ): void {
	// Validate post ID
	$post_id = dynos_validate_post_id( $post_id );
	if ( false === $post_id ) {
		return;
	}

	// Verify nonce
	if (
		! isset( $_POST['dynos_service_faqs_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dynos_service_faqs_nonce'] ) ), 'dynos_save_service_faqs' )
	) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check if this is a revision
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// Check post type and permissions
	$settings     = function_exists( 'dynos_sanitize_cpt_settings' ) ? dynos_sanitize_cpt_settings() : array();
	$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';
	if ( $service_slug !== $post_type ) {
		return;
	}

	// Check user capabilities
	$post_type_obj = get_post_type_object( $post_type );
	if ( ! $post_type_obj || ! current_user_can( $post_type_obj->cap->edit_post, $post_id ) ) {
		return;
	}

	// Process FAQs from POST data
	// Questions are sanitized as plain text, answers allow HTML (wp_kses_post)
	$faqs_questions = isset( $_POST['faqs_question'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['faqs_question'] ) ) : array();
	$faqs_answers   = isset( $_POST['faqs_answer'] ) ? array_map( 'wp_kses_post', wp_unslash( $_POST['faqs_answer'] ) ) : array();

	// Validate arrays have same length
	// This ensures each question has a corresponding answer
	$count = count( $faqs_questions );
	if ( count( $faqs_answers ) !== $count ) {
		// Log error but continue processing with minimum count
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			dynos_log_error(
				'FAQ questions and answers count mismatch for post ID: ' . $post_id,
				'warning',
				array( 'post_id' => $post_id )
			);
		}
		// Fire action for error handling
		do_action( 'dynos_faq_count_mismatch', $post_id, $count, count( $faqs_answers ) );
		$count = min( $count, count( $faqs_answers ) );
	}

	// Build structured FAQ array from parallel question/answer arrays
	$new_faqs = array();
	if ( ! empty( $faqs_questions ) && $count > 0 ) {
		for ( $i = 0; $i < $count; $i++ ) {
			$question = isset( $faqs_questions[ $i ] ) ? trim( $faqs_questions[ $i ] ) : '';
			$answer   = isset( $faqs_answers[ $i ] ) ? trim( $faqs_answers[ $i ] ) : '';

			// Only save if question is not empty and both are valid strings
			// Empty questions are skipped (allows partial FAQ deletion)
			if ( ! empty( $question ) && is_string( $question ) && is_string( $answer ) ) {
				$new_faqs[] = array(
					'question' => $question,
					'answer'   => $answer,
				);
			}
		}
	}

	// Allow filtering before saving
	$new_faqs = apply_filters( 'dynos_before_save_faqs', $new_faqs, $post_id );

	// Save or delete FAQs
	if ( ! empty( $new_faqs ) ) {
		update_post_meta( $post_id, 'service_faqs', $new_faqs );
	} else {
		delete_post_meta( $post_id, 'service_faqs' );
	}

	// Fire action after saving
	do_action( 'dynos_after_save_faqs', $post_id, $new_faqs );
}
add_action( 'save_post', 'dynos_save_service_faqs_meta_box' );
