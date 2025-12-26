/* global gtag, fbq */

/**
 * WhatsApp Scripts
 *
 * @package
 */

document.addEventListener( 'DOMContentLoaded', function () {
	const wrapper = document.querySelector( '.dynos-whatsapp-wrapper' );
	const cta = document.querySelector( '.dynos-whatsapp-cta' );

	if ( wrapper ) {
		// 1. Initial CTA Display Logic
		if ( cta ) {
			const delay = parseInt( cta.getAttribute( 'data-delay' ), 10 ) || 0;
			setTimeout( function () {
				cta.style.display = 'block';
			}, delay * 1000 );
		}

		// 2. Drag and Drop Logic
		if ( wrapper.getAttribute( 'data-draggable' ) === 'true' ) {
			initDraggable( wrapper );
		}
	}

	// Exit Intent (Show if not already shown)
	document.addEventListener( 'mouseleave', function ( e ) {
		if ( e.clientY < 0 && cta && cta.style.display === 'none' ) {
			cta.style.display = 'block';
		}
	} );
} );

/**
 * Initialize Draggable Logic
 * @param {HTMLElement} elm
 */
function initDraggable( elm ) {
	let pos1 = 0,
		pos2 = 0,
		pos3 = 0,
		pos4 = 0;
	let isDragging = false;
	const buttonLink = elm.querySelector( 'a' );

	// Restore position from localStorage
	const savedPos = localStorage.getItem( 'dynos_whatsapp_pos' );
	if ( savedPos ) {
		const pos = JSON.parse( savedPos );
		elm.style.top = pos.top;
		elm.style.left = pos.left;
		// Reset other positioning to avoid conflicts
		elm.style.bottom = 'auto';
		elm.style.right = 'auto';
		elm.style.insetBlockEnd = 'auto';
		elm.style.insetInlineEnd = 'auto';
		elm.style.insetInlineStart = 'auto';
	} else {
		// Initial setup to allow dragging from current position
		// We need to compute current strictly to set top/left for the first drag
		// actually, we can wait for first mousedown to convert to absolute/fixed coords
	}

	elm.onmousedown = dragMouseDown;
	elm.ontouchstart = dragMouseDown;

	function dragMouseDown( e ) {
		// e = e || window.event; // Standardize event
		// Allow clicking the close button on CTA without dragging
		if ( e.target.classList.contains( 'dynos-cta-close' ) ) {
			return;
		}

		// Prevent default unless it's a touch event (we might want scrolling? No, preventing default on touchstart stops scrolling, which is good for dragging)
		// But if we preventDefault on touchstart, links inside won't click?
		// e.preventDefault();

		isDragging = false;
		// Get initial cursor position
		pos3 = e.clientX || ( e.touches ? e.touches[ 0 ].clientX : 0 );
		pos4 = e.clientY || ( e.touches ? e.touches[ 0 ].clientY : 0 );

		document.onmouseup = closeDragElement;
		document.onmousemove = elementDrag;
		document.ontouchend = closeDragElement;
		document.ontouchmove = elementDrag;
	}

	function elementDrag( e ) {
		isDragging = true;
		// e = e || window.event;
		// e.preventDefault(); // Prevent text selection/scrolling

		// Calculate new cursor position
		const clientX = e.clientX || ( e.touches ? e.touches[ 0 ].clientX : 0 );
		const clientY = e.clientY || ( e.touches ? e.touches[ 0 ].clientY : 0 );

		pos1 = pos3 - clientX;
		pos2 = pos4 - clientY;
		pos3 = clientX;
		pos4 = clientY;

		// Set the element's new position
		// We first need to ensure we have swapped to top/left positioning if not already
		if ( elm.style.top === '' || elm.style.bottom !== 'auto' ) {
			const rect = elm.getBoundingClientRect();
			elm.style.top = rect.top + 'px';
			elm.style.left = rect.left + 'px';
			elm.style.bottom = 'auto';
			elm.style.right = 'auto';
			elm.style.insetBlockEnd = 'auto';
			elm.style.insetInlineEnd = 'auto';
			elm.style.insetInlineStart = 'auto';
		}

		// Boundary checks (optional, but good)
		let newTop = elm.offsetTop - pos2;
		let newLeft = elm.offsetLeft - pos1;

		// Simple window bounds
		const winWidth = window.innerWidth;
		const winHeight = window.innerHeight;
		const elWidth = elm.offsetWidth;
		const elHeight = elm.offsetHeight;

		// Boundary logic
		if ( newTop < 0 ) {
			newTop = 0;
		}
		if ( newLeft < 0 ) {
			newLeft = 0;
		}
		if ( newTop + elHeight > winHeight ) {
			newTop = winHeight - elHeight;
		}
		if ( newLeft + elWidth > winWidth ) {
			newLeft = winWidth - elWidth;
		}

		elm.style.top = newTop + 'px';
		elm.style.left = newLeft + 'px';
	}

	function closeDragElement() {
		// Stop moving when mouse button is released:
		document.onmouseup = null;
		document.onmousemove = null;
		document.ontouchend = null;
		document.ontouchmove = null;

		if ( isDragging ) {
			// Save position
			const pos = {
				top: elm.style.top,
				left: elm.style.left,
			};
			localStorage.setItem( 'dynos_whatsapp_pos', JSON.stringify( pos ) );

			// Prevent accidental click if it was a drag
			// We need to add a temporary click block
			if ( buttonLink ) {
				const preventClick = function ( clickE ) {
					clickE.preventDefault();
					clickE.stopPropagation();
					buttonLink.removeEventListener( 'click', preventClick );
				};
				buttonLink.addEventListener( 'click', preventClick );
			}
		}
	}
}

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
