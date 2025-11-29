<?php
/**
 * Plugin Deactivator
 *
 * Handles plugin deactivation tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin deactivation hook.
 *
 * @since 1.1.0
 */
function doc_deactivate_plugin(): void {
    // Flush rewrite rules
    flush_rewrite_rules();

    // Clear any transients
    delete_transient('doc_plugin_activated');
}

