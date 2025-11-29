<?php
/**
 * Settings Page Functionality
 *
 * Main settings page file that includes registration and renderer.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

// Settings registration and renderer are already included in main plugin file

/**
 * Add admin menu for plugin settings.
 *
 * @since 1.1.0
 */
function doc_add_admin_menu(): void {
    add_options_page(
        __('Dynamic Online Services Settings', 'dynamic-online-services'),
        __('Dynamic Services', 'dynamic-online-services'),
        'manage_options',
        'dynamic-online-services',
        'doc_settings_page_html'
    );
}
add_action('admin_menu', 'doc_add_admin_menu');
