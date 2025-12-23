/**
 * FAQs Admin JavaScript
 *
 * Handles admin interface for managing FAQs on service pages.
 * Provides add/remove functionality and dynamic form generation.
 *
 * @param      $
 * @package
 * @subpackage Assets/JavaScript
 * @since      1.0.0
 */

( function ( $ ) {
	'use strict';

	// Constants
	const SELECTOR_ADD_BUTTON = '#add-faq';
	const SELECTOR_CONTAINER = '#faqs-container';
	const SELECTOR_REMOVE_BUTTON = '.remove-faq';
	const CLASS_FAQ_ITEM = 'faq-item';

	/**
	 * Counter for generating unique FAQ item IDs.
	 *
	 * @type {number}
	 * @since 1.0.0
	 */
	let faqIdCounter = 0;

	/**
	 * Generate HTML template for a new FAQ item.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} labels               Object containing translated label strings.
	 * @param {string} labels.questionLabel Label for question field.
	 * @param {string} labels.answerLabel   Label for answer field.
	 * @param {string} labels.removeLabel   Label for remove button.
	 * @param {number} itemId               Unique ID for this FAQ item.
	 *
	 * @return {string} HTML string for the FAQ item.
	 */
	function createFaqItemTemplate( labels, itemId ) {
		// Escape label strings to prevent XSS
		const safeQuestionLabel = $( '<div>' )
			.text( labels.questionLabel )
			.html();
		const safeAnswerLabel = $( '<div>' ).text( labels.anserLabel ).html();
		const safeRemoveLabel = $( '<div>' ).text( labels.removeLabel ).html();

		// Build HTML using safe labels
		return (
			'<div class="' +
			CLASS_FAQ_ITEM +
			'" data-faq-id="' +
			itemId +
			'" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; position: relative;">' +
			'<label for="faq-question-' +
			itemId +
			'">' +
			safeQuestionLabel +
			'</label>' +
			'<input type="text" id="faq-question-' +
			itemId +
			'" name="faqs_question[]" value="" class="widefat" style="width: 100%; margin-bottom: 5px;" />' +
			'<label for="faq-answer-' +
			itemId +
			'">' +
			safeAnswerLabel +
			'</label>' +
			'<textarea id="faq-answer-' +
			itemId +
			'" name="faqs_answer[]" class="widefat" style="width: 100%; min-height: 80px;"></textarea>' +
			'<button type="button" class="button ' +
			SELECTOR_REMOVE_BUTTON.substring( 1 ) +
			'" style="margin-top: 5px;">' +
			safeRemoveLabel +
			'</button>' +
			'</div>'
		);
	}

	/**
	 * Add a new FAQ item to the container.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $container The FAQ container element.
	 * @param {Object} labels     Object containing translated label strings.
	 *
	 * @return {void}
	 */
	function addFaqItem( $container, labels ) {
		// Generate unique ID for this FAQ
		faqIdCounter++;
		const itemId = Date.now() + '-' + faqIdCounter;

		// Create and append FAQ item
		const html = createFaqItemTemplate( labels, itemId );
		$container.append( html );

		// Focus on the new question input for better UX
		$( '#faq-question-' + itemId ).focus();

		// Log addition for debugging
		if ( typeof window.sosConsoleLog === 'function' ) {
			window.sosConsoleLog( 'log', 'FAQ item added with ID:', itemId );
		}
	}

	/**
	 * Remove an FAQ item from the container.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery}  $item   The FAQ item element to remove.
	 * @param {boolean} confirm Whether to show confirmation dialog.
	 *
	 * @return {void}
	 */
	function removeFaqItem( $item, confirm ) {
		// Validate item exists
		if ( ! $item || $item.length === 0 ) {
			return;
		}

		// Show confirmation if requested and item has content
		if ( confirm ) {
			const $question = $item.find( 'input[name="faqs_question[]"]' );
			const $answer = $item.find( 'textarea[name="faqs_answer[]"]' );
			const hasContent =
				( $question.val() && $question.val().trim() !== '' ) ||
				( $answer.val() && $answer.val().trim() !== '' );

			if ( hasContent ) {
				// eslint-disable-next-line no-alert -- User confirmation is intentional UX
				const confirmRemoval = window.confirm(
					'Are you sure you want to remove this FAQ? Any unsaved content will be lost.'
				);
				if ( ! confirmRemoval ) {
					return;
				}
			}
		}

		// Get FAQ ID for logging before removal
		const itemId = $item.data( 'faq-id' );

		// Remove the item
		$item.fadeOut( 300, function () {
			$( this ).remove();

			// Log removal for debugging
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'log',
					'FAQ item removed with ID:',
					itemId
				);
			}
		} );
	}

	/**
	 * Initialize FAQ admin functionality.
	 *
	 * Sets up event handlers for adding and removing FAQs.
	 *
	 * @since 1.0.0
	 *
	 * @return {void}
	 */
	function initializeFaqAdmin() {
		// Defensive check: ensure required object exists
		if ( typeof dynosFaqsAdmin === 'undefined' ) {
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'error',
					'DYNOS FAQs Admin: dynosFaqsAdmin object not found'
				);
			}
			// Show user-friendly error message in admin
			if ( typeof window.sosShowAdminError === 'function' ) {
				window.sosShowAdminError(
					'DYNOS FAQs Admin: Configuration error. Please refresh the page or contact support if the issue persists.'
				);
			}
			return;
		}

		// Extract labels with fallbacks
		const labels = {
			questionLabel: dynosFaqsAdmin.questionLabel || 'Question:',
			answerLabel: dynosFaqsAdmin.answerLabel || 'Answer:',
			removeLabel: dynosFaqsAdmin.removeLabel || 'Remove FAQ',
		};

		// Defensive check: ensure container exists
		const $faqsContainer = $( SELECTOR_CONTAINER );
		if ( $faqsContainer.length === 0 ) {
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'warn',
					'SOS FAQs Admin: ' +
						SELECTOR_CONTAINER +
						' not found - this is normal if not on a service edit page'
				);
			}
			return;
		}

		// Initialize counter based on existing items
		faqIdCounter = $faqsContainer.find( '.' + CLASS_FAQ_ITEM ).length;

		// Handle add FAQ button click
		const $addFaqButton = $( SELECTOR_ADD_BUTTON );
		if ( $addFaqButton.length === 0 ) {
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'warn',
					'SOS FAQs Admin: ' +
						SELECTOR_ADD_BUTTON +
						' button not found'
				);
			}
			return;
		}

		$addFaqButton.on( 'click', function () {
			addFaqItem( $faqsContainer, labels );
		} );

		// Handle remove FAQ button click (using event delegation for dynamic elements)
		$faqsContainer.on( 'click', SELECTOR_REMOVE_BUTTON, function () {
			const $faqItem = $( this ).closest( '.' + CLASS_FAQ_ITEM );
			removeFaqItem( $faqItem, true ); // true = show confirmation
		} );

		// Log successful initialization
		if ( typeof window.sosConsoleLog === 'function' ) {
			window.sosConsoleLog(
				'log',
				'SOS FAQs Admin initialized successfully'
			);
		}
	}

	/**
	 * Initialize on document ready.
	 */
	$( document ).ready( function () {
		try {
			initializeFaqAdmin();
		} catch ( error ) {
			// Log error to console in development
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog( 'error', 'SOS FAQs Admin Error:', error );
			}
			// Show user-friendly error message
			if ( typeof window.sosShowAdminError === 'function' ) {
				window.sosShowAdminError(
					'SOS FAQs Admin: An error occurred. Please try refreshing the page. If the problem persists, contact support.'
				);
			}
		}
	} );
} )( jQuery );
