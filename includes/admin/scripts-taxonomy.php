<?php
/**
 * Taxonomy Admin Scripts
 *
 * Handles enqueuing of admin scripts for taxonomy pages.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts for the media uploader on taxonomy add/edit forms.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function doc_enqueue_service_category_admin_scripts($hook_suffix): void {
    // Check if we're on the taxonomy add or edit page
    $is_add_form    = doc_is_taxonomy_add_screen('services_category');
    $is_edit_form   = doc_is_taxonomy_edit_screen('services_category');
    $is_edit_screen = doc_is_current_screen('edit-tags', '', 'services_category');

    if (!$is_add_form && !$is_edit_form && !$is_edit_screen) {
        return;
    }

    wp_enqueue_media();

    // Enqueue common admin JavaScript first (for shared functions)
    wp_enqueue_script(
        'sos-admin-common',
        DOC_PLUGIN_URL . 'assets/js/admin-common.js',
        array('jquery'),
        DOC_VERSION,
        true
    );

    wp_enqueue_script(
        'sos-taxonomy-media-uploader',
        DOC_PLUGIN_URL . 'assets/js/taxonomy-media-uploader.js',
        array('jquery', 'sos-admin-common'),
        DOC_VERSION,
        true
    );

    // Localize script for translations
    wp_localize_script(
        'sos-taxonomy-media-uploader',
        'sosTaxonomyMedia',
        array(
            'title'  => __('Choose Thumbnail', 'dynamic-online-services'),
            'button' => __('Choose Image', 'dynamic-online-services'),
        )
    );
}
add_action('admin_enqueue_scripts', 'doc_enqueue_service_category_admin_scripts');

