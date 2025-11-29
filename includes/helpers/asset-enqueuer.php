<?php
/**
 * Asset Enqueuer Helper Functions
 *
 * Handles enqueuing of scripts and styles.
 * Separates asset enqueuing logic from rendering logic (SRP).
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue FAQ accordion assets (scripts and styles).
 *
 * @since 1.1.0
 * @return void
 */
function doc_enqueue_faq_accordion_assets(): void {
    wp_enqueue_style(
        'sos-faqs-accordion',
        DOC_PLUGIN_URL . 'assets/css/faqs-accordion.css',
        array(),
        DOC_VERSION
    );
    wp_enqueue_script(
        'sos-faqs-accordion',
        DOC_PLUGIN_URL . 'assets/js/faqs-accordion.js',
        array('jquery'),
        DOC_VERSION,
        true
    );
}

/**
 * Enqueue service card styles.
 *
 * @since 1.1.0
 * @return void
 */
function doc_enqueue_service_card_styles_asset(): void {
    wp_enqueue_style(
        'sos-service-cards',
        DOC_PLUGIN_URL . 'assets/css/service-cards.css',
        array(),
        DOC_VERSION
    );
}

