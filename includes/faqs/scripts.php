<?php
/**
 * FAQs Admin Scripts
 *
 * Handles enqueuing of admin scripts for FAQ meta box.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue FAQ admin scripts.
 *
 * @since 1.1.0
 * @param string $hook_suffix Current admin page hook suffix.
 * @return void
 */
function doc_enqueue_faqs_admin_scripts($hook_suffix): void {
    // Only load on service post edit screens
    if (!doc_is_post_edit_screen('service')) {
        return;
    }

    // Enqueue common admin JavaScript first (for shared functions)
    wp_enqueue_script(
        'sos-admin-common',
        DOC_PLUGIN_URL . 'assets/js/admin-common.js',
        array('jquery'),
        DOC_VERSION,
        true
    );

    wp_enqueue_script(
        'sos-faqs-admin',
        DOC_PLUGIN_URL . 'assets/js/faqs-admin.js',
        array('jquery', 'sos-admin-common'),
        DOC_VERSION,
        true
    );

    // Localize script for translations
    wp_localize_script(
        'sos-faqs-admin',
        'sosFaqsAdmin',
        array(
            'questionLabel' => __('Question:', 'dynamic-online-services'),
            'answerLabel'   => __('Answer:', 'dynamic-online-services'),
            'removeLabel'   => __('Remove FAQ', 'dynamic-online-services'),
        )
    );
}
add_action('admin_enqueue_scripts', 'doc_enqueue_faqs_admin_scripts');

