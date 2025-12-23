/**
 * Admin Common JavaScript
 *
 * Shared utility functions for WordPress admin functionality.
 * Provides safe console logging and user-friendly error notifications.
 *
 * @param      $
 * @package
 * @subpackage Assets/JavaScript
 * @since      1.0.0
 */

( function ( $ ) {
	'use strict';

	// Constants for notice behavior
	const NOTICE_AUTO_DISMISS_DELAY = 10000; // 10 seconds in milliseconds
	const NOTICE_FADE_DURATION = 400; // Default jQuery fade duration

	// Valid console methods: log, error, warn, info, debug

	/**
	 * Safe console logging helper.
	 *
	 * Checks if console is available and the requested method exists before logging.
	 * Gracefully degrades if console is not available (e.g., in older browsers).
	 *
	 * @since 1.0.0
	 *
	 * @param {string} method Console method to use ('log', 'error', 'warn', 'info', 'debug').
	 *
	 * @return {void}
	 *
	 * @example
	 * window.sosConsoleLog('log', 'Debug message');
	 * window.sosConsoleLog('error', 'Error occurred:', errorObject);
	 */
	window.sosConsoleLog = function ( method ) {
		// Validate method parameter
		if ( typeof method !== 'string' || ! method ) {
			// Silently fail for invalid method - avoid infinite recursion
			return;
		}

		// Check if console is available and method exists
		if (
			typeof console !== 'undefined' &&
			console[ method ] &&
			typeof console[ method ] === 'function'
		) {
			// Extract additional arguments (everything after 'method')
			const logArgs = Array.prototype.slice.call( arguments, 1 );

			// Call the console method with all arguments
			console[ method ].apply( console, logArgs );
		}
	};

	/**
	 * Display user-friendly error message in WordPress admin.
	 *
	 * Creates a dismissible notice similar to WordPress admin notices with
	 * automatic dismissal and manual close button support.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} message             Error message to display to the user.
	 * @param {Object} options             Optional configuration object.
	 * @param {string} options.type        Notice type: 'error', 'warning', 'success', 'info'. Default: 'error'.
	 * @param {number} options.autoDismiss Auto-dismiss delay in milliseconds. Default: 10000. Set to 0 to disable.
	 *
	 * @return {void}
	 *
	 * @example
	 * window.sosShowAdminError('An error occurred while saving.');
	 * window.sosShowAdminError('Settings saved!', { type: 'success' });
	 */
	window.sosShowAdminError = function ( message, options ) {
		// Validate message parameter
		if ( typeof message === 'undefined' || ! message ) {
			window.sosConsoleLog(
				'warn',
				'sosShowAdminError called without a message'
			);
			return;
		}

		// Ensure message is a string
		if ( typeof message !== 'string' ) {
			message = String( message );
		}

		// Parse options with defaults
		const settings = $.extend(
			{
				type: 'error',
				autoDismiss: NOTICE_AUTO_DISMISS_DELAY,
			},
			options || {}
		);

		// Validate notice type
		const validTypes = [ 'error', 'warning', 'success', 'info' ];
		if ( validTypes.indexOf( settings.type ) === -1 ) {
			settings.type = 'error';
		}

		// Check if we're in admin context
		if ( typeof $ === 'undefined' ) {
			window.sosConsoleLog(
				'error',
				'sosShowAdminError: jQuery is not available'
			);
			return;
		}

		const $wpbodyContent = $( '#wpbody-content' );
		if ( ! $wpbodyContent.length ) {
			// Not in admin context, just log to console
			window.sosConsoleLog( settings.type, message );
			return;
		}

		// Sanitize message by creating a text node (prevents XSS)
		const sanitizedMessage = $( '<div>' ).text( message ).html();

		// Create notice element with appropriate type class
		const noticeHtml =
			'<div class="notice notice-error dynos-js-notice is-dismissible dynos-js-notice"><p>' +
			sanitizedMessage +
			'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		const $notice = $( noticeHtml );

		// Insert at top of content area
		$wpbodyContent.prepend( $notice );

		// Auto-dismiss after specified delay (if not disabled)
		if ( settings.autoDismiss > 0 ) {
			setTimeout( function () {
				$notice.fadeOut( NOTICE_FADE_DURATION, function () {
					$( this ).remove();
				} );
			}, settings.autoDismiss );
		}

		// Handle manual dismissal via WordPress dismiss button
		$notice.on( 'click', '.notice-dismiss', function () {
			$notice.fadeOut( NOTICE_FADE_DURATION, function () {
				$( this ).remove();
			} );
		} );

		// Log to console as well for debugging
		window.sosConsoleLog( settings.type, 'Admin Notice: ' + message );
	};

	/**
	 * Display success message in WordPress admin.
	 *
	 * Convenience wrapper for sosShowAdminError with type 'success'.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} message Success message to display.
	 * @param {Object} options Optional configuration object.
	 *
	 * @return {void}
	 *
	 * @example
	 * window.sosShowAdminSuccess('Settings saved successfully!');
	 */
	window.sosShowAdminSuccess = function ( message, options ) {
		const settings = $.extend( {}, options || {}, { type: 'success' } );
		window.sosShowAdminError( message, settings );
	};

	/**
	 * Display warning message in WordPress admin.
	 *
	 * Convenience wrapper for sosShowAdminError with type 'warning'.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} message Warning message to display.
	 * @param {Object} options Optional configuration object.
	 *
	 * @return {void}
	 *
	 * @example
	 * window.sosShowAdminWarning('This action cannot be undone.');
	 */
	window.sosShowAdminWarning = function ( message, options ) {
		const settings = $.extend( {}, options || {}, { type: 'warning' } );
		window.sosShowAdminError( message, settings );
	};
} )( jQuery );
