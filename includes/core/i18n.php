<?php
/**
 * Internationalization
 *
 * Handles plugin textdomain loading for translations.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load plugin textdomain for internationalization.
 *
 * @since 1.1.0
 */
function doc_load_textdomain(): void {
    load_plugin_textdomain(
        'dynamic-online-services',
        false,
        dirname(DOC_PLUGIN_BASENAME) . '/languages'
    );
}
add_action('plugins_loaded', 'doc_load_textdomain');

