/**
 * FAQs Admin JavaScript
 *
 * @param      $
 * @package
 * @subpackage Assets
 */

( function ( $ ) {
	'use strict';

	$( document ).ready( function () {
		try {
			// Defensive check: ensure required object exists
			if ( typeof sosFaqsAdmin === 'undefined' ) {
				if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'error',
						'SOS FAQs Admin: sosFaqsAdmin object not found'
					);
				}
				// Show user-friendly error message in admin
				if ( typeof window.sosShowAdminError === 'function' ) {
					window.sosShowAdminError(
						'SOS FAQs Admin: Configuration error. Please refresh the page or contact support if the issue persists.'
					);
				} else if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'error',
						'SOS FAQs Admin: Configuration error.'
					);
				}
				return;
			}

			const questionLabel = sosFaqsAdmin.questionLabel || 'Question:';
			const answerLabel = sosFaqsAdmin.answerLabel || 'Answer:';
			const removeLabel = sosFaqsAdmin.removeLabel || 'Remove FAQ';

			// Defensive check: ensure container exists
			const $faqsContainer = $( '#faqs-container' );
			if ( $faqsContainer.length === 0 ) {
				if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'warn',
						'SOS FAQs Admin: #faqs-container not found'
					);
				}
				return;
			}

			// Handle add FAQ button click
			const $addFaqButton = $( '#add-faq' );
			if ( $addFaqButton.length === 0 ) {
				if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'warn',
						'SOS FAQs Admin: #add-faq button not found'
					);
				}
				return;
			}

			$addFaqButton.on( 'click', function () {
				const html =
					'<div class="faq-item" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">' +
					'<label>' +
					questionLabel +
					'</label>' +
					'<input type="text" name="faqs_question[]" value="" style="width: 100%; margin-bottom: 5px;" />' +
					'<label>' +
					answerLabel +
					'</label>' +
					'<textarea name="faqs_answer[]" style="width: 100%; min-height: 80px;"></textarea>' +
					'<button type="button" class="button remove-faq" style="margin-top: 5px;">' +
					removeLabel +
					'</button>' +
					'</div>';
				$( '#faqs-container' ).append( html );
			} );

			// Handle remove FAQ button click (using event delegation)
			$faqsContainer.on( 'click', '.remove-faq', function () {
				$( this ).closest( '.faq-item' ).remove();
			} );
		} catch ( error ) {
			// Log error to console in development
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog( 'error', 'SOS FAQs Admin Error:', error );
			}
			// Use shared error handling function from admin-common.js
			if ( typeof window.sosShowAdminError === 'function' ) {
				window.sosShowAdminError(
					'SOS FAQs Admin: An error occurred. Please try refreshing the page. If the problem persists, contact support.'
				);
			} else if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'error',
					'SOS FAQs Admin: An error occurred. Please try refreshing the page.'
				);
			}
		}
	} );
} )( jQuery );
