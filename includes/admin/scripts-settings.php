<?php
/**
 * Settings Admin Scripts
 *
 * Handles enqueuing of admin scripts for settings page.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin scripts for settings page.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function doc_enqueue_settings_admin_scripts($hook_suffix): void {
    if ('settings_page_dynamic-online-services' !== $hook_suffix) {
        return;
    }

    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script(
        'wp-color-picker',
        false,
        array('jquery'),
        false,
        true
    );

    // Use proper script localization instead of inline script
    wp_add_inline_script(
        'wp-color-picker',
        'jQuery(document).ready(function($) {
            if ($.fn.wpColorPicker) {
                $(".doc-color-picker").wpColorPicker({
                    change: function(event, ui) {
                        $(this).trigger("change");
                    }
                });
            }
        });',
        'after'
    );
}
add_action('admin_enqueue_scripts', 'doc_enqueue_settings_admin_scripts');

