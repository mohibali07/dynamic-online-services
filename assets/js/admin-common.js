/**
 * Admin Common JavaScript
 *
 * Shared functions for admin functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Assets
 */

(function($) {
    'use strict';

    /**
     * Safe console logging helper.
     * Checks if console is available before logging.
     *
     * @param {string} method Console method ('log', 'error', 'warn', 'info').
     * @param {...*} args Arguments to pass to console method.
     */
    window.sosConsoleLog = function(method) {
        if (typeof console !== 'undefined' && console[method] && typeof console[method] === 'function') {
            var logArgs = Array.prototype.slice.call(arguments, 1);
            console[method].apply(console, logArgs);
        }
    };

    /**
     * Display user-friendly error message in WordPress admin.
     * Creates a dismissible notice similar to WordPress admin notices.
     *
     * @param {string} message Error message to display.
     */
    window.sosShowAdminError = function(message) {
        if (typeof message === 'undefined' || !message) {
            return;
        }
        
        // Check if we're in admin context
        if (typeof $ === 'undefined' || !$('#wpbody-content').length) {
            // Not in admin, just log to console
            window.sosConsoleLog('error', message);
            return;
        }
        
        // Create notice element
        var $notice = $('<div class="notice notice-error is-dismissible sos-js-notice"><p>' + 
                       $('<div>').text(message).html() + '</p></div>');
        
        // Insert at top of content area
        $('#wpbody-content').prepend($notice);
        
        // Auto-dismiss after 10 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 10000);
        
        // Handle manual dismissal
        $notice.on('click', '.notice-dismiss', function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        });
    };
})(jQuery);

