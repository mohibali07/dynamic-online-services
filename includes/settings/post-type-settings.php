<?php
/**
 * Post Type & Taxonomy Settings Registration
 *
 * Handles registration of post type and taxonomy settings fields.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register post type and taxonomy settings.
 *
 * @since 1.1.0
 */
function doc_register_post_type_settings(): void {
    add_settings_field(
        'service_post_type_slug',
        _x('Service Post Type Slug', 'Settings field label', 'dynamic-online-services'),
        'doc_text_field_callback',
        'Dynamic_Online_Services',
        'doc_post_type_section',
        array(
            'name'        => 'service_post_type_slug',
            'default'     => 'services',
            'placeholder' => 'e.g., services, my-services',
            'description' => __('URL slug for the service post type. Change requires flushing rewrite rules.', 'dynamic-online-services'),
            'label_for'   => 'service_post_type_slug',
        )
    );

    add_settings_field(
        'service_taxonomy_slug',
        _x('Service Taxonomy Slug', 'Settings field label', 'dynamic-online-services'),
        'doc_text_field_callback',
        'Dynamic_Online_Services',
        'doc_post_type_section',
        array(
            'name'        => 'service_taxonomy_slug',
            'default'     => 'service-category',
            'placeholder' => 'e.g., service-category, category',
            'description' => __('URL slug for the service category taxonomy. Change requires flushing rewrite rules.', 'dynamic-online-services'),
            'label_for'   => 'service_taxonomy_slug',
        )
    );

    add_settings_field(
        'service_menu_position',
        _x('Service Menu Position', 'Settings field label', 'dynamic-online-services'),
        'doc_text_field_callback',
        'Dynamic_Online_Services',
        'doc_post_type_section',
        array(
            'name'        => 'service_menu_position',
            'default'     => '',
            'placeholder' => 'e.g., 20, 30 (leave empty for default)',
            'description' => __('Menu position in WordPress admin. Lower numbers appear first. Leave empty for default position.', 'dynamic-online-services'),
            'label_for'   => 'service_menu_position',
        )
    );

    add_settings_field(
        'service_menu_icon',
        _x('Service Menu Icon', 'Settings field label', 'dynamic-online-services'),
        'doc_text_field_callback',
        'Dynamic_Online_Services',
        'doc_post_type_section',
        array(
            'name'        => 'service_menu_icon',
            'default'     => 'dashicons-admin-customizer',
            'placeholder' => 'e.g., dashicons-admin-customizer',
            'description' => __('Dashicon class name for the menu icon. See <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicons</a>.', 'dynamic-online-services'),
            'label_for'   => 'service_menu_icon',
        )
    );
}

