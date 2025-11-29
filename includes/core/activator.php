<?php
/**
 * Plugin Activator
 *
 * Handles plugin activation tasks.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin activation hook - migrate old options to new option name.
 *
 * @since 1.1.0
 */
function doc_activate_plugin(): void
{
    // Load error handler if not already loaded
    if (!function_exists('doc_handle_activation_error')) {
        require_once DOC_PLUGIN_DIR . 'includes/core/exception.php';
        require_once DOC_PLUGIN_DIR . 'includes/core/error-handler.php';
    }

    // Check requirements before activation
    if (!doc_check_requirements()) {
        deactivate_plugins(DOC_PLUGIN_BASENAME);
        // Set error transient instead of outputting (no output during activation)
        set_transient('doc_activation_error', esc_html__('Dynamic Online Services could not be activated. Please check the system requirements.', 'dynamic-online-services'), 30);
        return;
    }

    // Migrate old option names (ss_options, sos_options) to new option name (doc_options)
    $old_ss_options = get_option('ss_options', false);
    if (false !== $old_ss_options) {
        // If old ss_options exist and new options don't, migrate them
        $new_options = get_option('doc_options', false);
        if (false === $new_options) {
            update_option('doc_options', $old_ss_options);
        }
    }

    // Also migrate sos_options if it exists (from previous plugin name)
    $old_sos_options = get_option('sos_options', false);
    if (false !== $old_sos_options) {
        // If old sos_options exist and new options don't, migrate them
        $new_options = get_option('doc_options', false);
        if (false === $new_options) {
            update_option('doc_options', $old_sos_options);
        }
    }

    // Initialize slug options if they don't exist (for tracking changes)
    $current_options = get_option('doc_options', array());
    if (!isset($current_options['service_post_type_slug']) || empty($current_options['service_post_type_slug'])) {
        $defaults = \DynamicOnlineServices\Settings\Defaults::get_options();
        update_option('doc_previous_service_slug', $defaults['service_post_type_slug']);
        update_option('doc_previous_taxonomy_slug', $defaults['service_taxonomy_slug']);
    } else {
        // Store current slugs as previous slugs
        update_option('doc_previous_service_slug', $current_options['service_post_type_slug']);
        update_option('doc_previous_taxonomy_slug', $current_options['service_taxonomy_slug']);
    }

    // Ensure required files are loaded for activation
    if (!function_exists('doc_register_services_cpt_and_taxonomy')) {
        require_once DOC_PLUGIN_DIR . 'includes/post-types.php';
    }

    // Flush rewrite rules for custom post types
    if (function_exists('doc_register_services_cpt_and_taxonomy')) {
        doc_register_services_cpt_and_taxonomy();
    }
    flush_rewrite_rules();

    // Set activation flag for redirect or other purposes
    set_transient('doc_plugin_activated', true, 30);
}
