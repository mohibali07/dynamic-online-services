/**
 * FAQs Accordion JavaScript
 *
 * Provides accordion functionality for FAQ sections with keyboard navigation,
 * accessibility features, and smooth animations.
 *
 * @package    Dynamic_Online_Services
 * @subpackage Assets/JavaScript
 * @since      1.0.0
 */

( function () {
	'use strict';

	// Constants for animation and timing
	const ANIMATION_TIMING = 300; // Milliseconds for expand/collapse animation
	const SELECTOR_FAQ_QUESTION = '.faq-question';
	const SELECTOR_FAQ_ANSWER = '.faq-answer';
	const CLASS_ACTIVE = 'active';
	const CLASS_SHOW = 'show';

	/**
	 * Close all FAQ items except the specified one.
	 *
	 * @since 1.0.0
	 *
	 * @param {NodeList} allQuestions All FAQ question elements.
	 * @param {Element}  currentQuestion The question element to keep open (or null to close all).
	 *
	 * @return {void}
	 */
	function closeAllFaqsExcept( allQuestions, currentQuestion ) {
		if ( ! allQuestions || allQuestions.length === 0 ) {
			return;
		}

		allQuestions.forEach( function ( question ) {
			// Skip the current question
			if ( question === currentQuestion ) {
				return;
			}

			// Remove active state
			question.classList.remove( CLASS_ACTIVE );
			question.setAttribute( 'aria-expanded', 'false' );

			// Hide the answer
			const answer = question.nextElementSibling;
			if ( answer && answer.classList.contains( 'faq-answer' ) ) {
				answer.classList.remove( CLASS_SHOW );
				answer.setAttribute( 'aria-hidden', 'true' );
			}
		} );
	}

	/**
	 * Toggle a single FAQ item open or closed.
	 *
	 * @since 1.0.0
	 *
	 * @param {Element}  question The FAQ question element to toggle.
	 * @param {boolean}  isActive Whether the FAQ is currently active/open.
	 * @param {NodeList} allQuestions All FAQ question elements (for closing others).
	 *
	 * @return {void}
	 */
	function toggleFaqItem( question, isActive, allQuestions ) {
		if ( ! question ) {
			return;
		}

		const answer = question.nextElementSibling;

		// Validate that answer element exists and is the correct element
		if ( ! answer || ! answer.classList.contains( 'faq-answer' ) ) {
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'warn',
					'FAQ answer element not found for question:',
					question
				);
			}
			return;
		}

		// Close all other FAQs first
		closeAllFaqsExcept( allQuestions, question );

		// Toggle current FAQ
		if ( isActive ) {
			// Close this FAQ
			question.classList.remove( CLASS_ACTIVE );
			question.setAttribute( 'aria-expanded', 'false' );
			answer.classList.remove( CLASS_SHOW );
			answer.setAttribute( 'aria-hidden', 'true' );
		} else {
			// Open this FAQ
			question.classList.add( CLASS_ACTIVE );
			question.setAttribute( 'aria-expanded', 'true' );
			answer.classList.add( CLASS_SHOW );
			answer.setAttribute( 'aria-hidden', 'false' );

			// Set focus to the opened answer for screen readers
			// Use tabindex -1 to make it programmatically focusable
			answer.setAttribute( 'tabindex', '-1' );
			answer.focus( { preventScroll: true } );

			// Remove tabindex after focus to maintain natural tab order
			setTimeout( function () {
				answer.removeAttribute( 'tabindex' );
			}, 100 );
		}
	}

	/**
	 * Handle keyboard navigation for FAQ questions.
	 *
	 * Supports Enter, Space, and arrow keys for accessibility.
	 *
	 * @since 1.0.0
	 *
	 * @param {KeyboardEvent} event The keyboard event.
	 * @param {Element}       question The FAQ question element.
	 *
	 * @return {void}
	 */
	function handleKeyboardNavigation( event, question ) {
		const key = event.key;

		// Handle Enter and Space to toggle
		if ( key === 'Enter' || key === ' ' ) {
			event.preventDefault();
			question.click();
			return;
		}

		// Handle arrow keys for navigation between questions
		const allQuestions = Array.from(
			document.querySelectorAll( SELECTOR_FAQ_QUESTION )
		);
		const currentIndex = allQuestions.indexOf( question );

		if ( currentIndex === -1 ) {
			return;
		}

		let targetIndex = currentIndex;

		if ( key === 'ArrowDown' || key === 'Down' ) {
			event.preventDefault();
			// Move to next question
			targetIndex = ( currentIndex + 1 ) % allQuestions.length;
		} else if ( key === 'ArrowUp' || key === 'Up' ) {
			event.preventDefault();
			// Move to previous question
			targetIndex =
				( currentIndex - 1 + allQuestions.length ) %
				allQuestions.length;
		} else if ( key === 'Home' ) {
			event.preventDefault();
			// Move to first question
			targetIndex = 0;
		} else if ( key === 'End' ) {
			event.preventDefault();
			// Move to last question
			targetIndex = allQuestions.length - 1;
		}

		// Focus the target question
		if ( targetIndex !== currentIndex && allQuestions[ targetIndex ] ) {
			allQuestions[ targetIndex ].focus();
		}
	}

	/**
	 * Initialize FAQ accordion functionality.
	 *
	 * Sets up click and keyboard event handlers for all FAQ questions.
	 *
	 * @since 1.0.0
	 *
	 * @return {void}
	 */
	function initializeFaqAccordion() {
		const faqQuestions = document.querySelectorAll( SELECTOR_FAQ_QUESTION );

		// Defensive check: return early if no FAQ questions found
		if ( ! faqQuestions || faqQuestions.length === 0 ) {
			return;
		}

		faqQuestions.forEach( function ( question, index ) {
			// Ensure proper ARIA attributes are set
			if ( ! question.hasAttribute( 'role' ) ) {
				question.setAttribute( 'role', 'button' );
			}
			if ( ! question.hasAttribute( 'aria-expanded' ) ) {
				question.setAttribute( 'aria-expanded', 'false' );
			}
			if ( ! question.hasAttribute( 'tabindex' ) ) {
				question.setAttribute( 'tabindex', '0' );
			}

			// Add unique ID for accessibility if not present
			if ( ! question.id ) {
				question.id = 'faq-question-' + index;
			}

			// Link answer to question via aria-controls
			const answer = question.nextElementSibling;
			if ( answer && answer.classList.contains( 'faq-answer' ) ) {
				if ( ! answer.id ) {
					answer.id = 'faq-answer-' + index;
				}
				question.setAttribute( 'aria-controls', answer.id );
				answer.setAttribute( 'aria-labelledby', question.id );
				answer.setAttribute( 'aria-hidden', 'true' );
			}

			// Handle click events
			question.addEventListener( 'click', function () {
				const isActive = this.classList.contains( CLASS_ACTIVE );
				toggleFaqItem( this, isActive, faqQuestions );
			} );

			// Handle keyboard navigation
			question.addEventListener( 'keydown', function ( e ) {
				handleKeyboardNavigation( e, this );
			} );
		} );
	}

	/**
	 * Initialize on DOM Content Loaded.
	 */
	document.addEventListener( 'DOMContentLoaded', function () {
		try {
			initializeFaqAccordion();
		} catch ( error ) {
			// Log error to console in development
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'error',
					'SOS FAQs Accordion Error:',
					error
				);
			}
		}
	} );
} )();
