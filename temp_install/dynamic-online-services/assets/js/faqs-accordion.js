/**
 * FAQs Accordion JavaScript
 *
 * @package
 * @subpackage Assets
 */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		try {
			const faqQuestions = document.querySelectorAll( '.faq-question' );

			// Defensive check: return early if no FAQ questions found
			if ( ! faqQuestions || faqQuestions.length === 0 ) {
				return;
			}

			faqQuestions.forEach( function ( question ) {
				question.addEventListener( 'click', function () {
					const answer = this.nextElementSibling;
					const isActive = this.classList.contains( 'active' );

					// Close all other FAQs
					faqQuestions.forEach( function ( otherQuestion ) {
						if ( otherQuestion !== question ) {
							otherQuestion.classList.remove( 'active' );
							otherQuestion.setAttribute(
								'aria-expanded',
								'false'
							);
							const otherAnswer =
								otherQuestion.nextElementSibling;
							if ( otherAnswer ) {
								otherAnswer.classList.remove( 'show' );
							}
						}
					} );

					// Toggle current FAQ
					if ( isActive ) {
						this.classList.remove( 'active' );
						this.setAttribute( 'aria-expanded', 'false' );
						answer.classList.remove( 'show' );
					} else {
						this.classList.add( 'active' );
						this.setAttribute( 'aria-expanded', 'true' );
						answer.classList.add( 'show' );
					}
				} );

				// Handle keyboard navigation (Enter and Space keys)
				question.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' ) {
						e.preventDefault();
						this.click();
					}
				} );
			} );
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
