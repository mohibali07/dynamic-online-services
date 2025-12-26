import { useEffect } from '@wordpress/element';

/**
 * Custom hook for keyboard shortcuts
 *
 * @param {string}   key                    - The key to listen for (without modifier)
 * @param {Function} callback               - Function to call when shortcut is pressed
 * @param {Object}   options                - Configuration options
 * @param {boolean}  options.requireCtrl    - Require Ctrl/Cmd key (default: true)
 * @param {boolean}  options.preventDefault - Prevent default behavior (default: true)
 */
export const useKeyboardShortcut = (
	key,
	callback,
	{ requireCtrl = true, preventDefault = true } = {}
) => {
	useEffect( () => {
		const handler = ( event ) => {
			// Check if the key matches
			const keyMatches = event.key.toLowerCase() === key.toLowerCase();

			// Check modifier requirement
			const modifierMatches = requireCtrl
				? event.metaKey || event.ctrlKey
				: true;

			// Don't trigger if user is typing in an input
			const isTyping = [ 'INPUT', 'TEXTAREA', 'SELECT' ].includes(
				event.target.tagName
			);

			if ( keyMatches && modifierMatches && ! isTyping ) {
				if ( preventDefault ) {
					event.preventDefault();
				}
				callback( event );
			}
		};

		document.addEventListener( 'keydown', handler );

		return () => {
			document.removeEventListener( 'keydown', handler );
		};
	}, [ key, callback, requireCtrl, preventDefault ] );
};
