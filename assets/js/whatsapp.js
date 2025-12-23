/* global gtag, fbq */

/**
 * WhatsApp Scripts
 *
 * @package
 */

document.addEventListener( 'DOMContentLoaded', function () {
	const cta = document.querySelector( '.dynos-whatsapp-cta' );
	if ( cta ) {
		const delay = parseInt( cta.getAttribute( 'data-delay' ), 10 ) || 0;
		setTimeout( function () {
			cta.style.display = 'block';
		}, delay * 1000 );
	}
	// Exit Intent (Show if not already shown)
	document.addEventListener( 'mouseleave', function ( e ) {
		if ( e.clientY < 0 && cta && cta.style.display === 'none' ) {
			cta.style.display = 'block';
		}
	} );
} );

/**
 * Handle WhatsApp click analytics
 * @param {HTMLElement} element
 */
window.dynosWhatsAppClick = function ( element ) {
	if ( element.dataset.analytics === 'true' ) {
		if ( typeof gtag === 'function' ) {
			gtag( 'event', 'click', {
				event_category: 'Contact',
				event_label: 'WhatsApp',
				transport_type: 'beacon',
			} );
		}
		if ( typeof fbq === 'function' ) {
			fbq( 'track', 'Contact' );
		}
	}
};

/**
 * Toggle agent selection modal
 * @param {Event} event
 */
window.dynosToggleAgentModal = function ( event ) {
	event.preventDefault();
	const modal = document.getElementById( 'dynos-agent-modal' );
	if ( modal ) {
		modal.style.display =
			modal.style.display === 'none' || modal.style.display === ''
				? 'flex'
				: 'none';
	}
};

// Close modal when clicking outside
window.onclick = function ( event ) {
	const modal = document.getElementById( 'dynos-agent-modal' );
	if ( modal && event.target === modal ) {
		modal.style.display = 'none';
	}
};
