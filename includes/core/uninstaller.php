<?php
/**
 * Plugin Uninstaller
 *
 * Handles plugin uninstall tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin uninstall hook.
 *
 * @since 1.1.0
 */
function doc_uninstall_plugin(): void {
    // Check if user has permission to uninstall
    if (!current_user_can('activate_plugins')) {
        return;
    }

    // Check if we should delete data on uninstall
    $delete_data = get_option('doc_delete_data_on_uninstall', false);

    if ($delete_data) {
        // Delete plugin options
        delete_option('doc_options');
        delete_option('doc_options'); // Clean up old option name
        delete_option('ss_options'); // Clean up old option name
        delete_option('doc_delete_data_on_uninstall');

        // Note: We don't delete custom post types and taxonomies data
        // as this could be destructive. Users should manually delete if needed.
    }

    // Clear rewrite rules
    flush_rewrite_rules();
}

