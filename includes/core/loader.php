<?php
/**
 * Plugin Loader
 *
 * Handles loading of all plugin files.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load all required plugin files.
 *
 * @since 1.1.0
 */
function doc_load_plugin_files(): void {
// Include core error handling first (order matters)
require_once DOC_PLUGIN_DIR . 'includes/core/exception.php';
require_once DOC_PLUGIN_DIR . 'includes/core/error-handler.php';

// Include required files (order matters - settings must come before helpers)
require_once DOC_PLUGIN_DIR . 'includes/settings-registration.php';
require_once DOC_PLUGIN_DIR . 'includes/settings-renderer.php';
require_once DOC_PLUGIN_DIR . 'includes/helpers.php';
require_once DOC_PLUGIN_DIR . 'includes/post-types.php';
require_once DOC_PLUGIN_DIR . 'includes/taxonomy-fields.php';
require_once DOC_PLUGIN_DIR . 'includes/faqs-meta-box.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes-faqs.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes-category.php';
require_once DOC_PLUGIN_DIR . 'includes/shortcodes-hero.php';
require_once DOC_PLUGIN_DIR . 'includes/settings-page.php';
require_once DOC_PLUGIN_DIR . 'includes/front-end-scripts.php';
require_once DOC_PLUGIN_DIR . 'includes/admin-scripts.php';

// Display activation error notice if activation failed (after helpers are loaded)
add_action('admin_notices', 'doc_display_activation_error_notice');
}

/**
 * Display activation error notice if activation failed.
 *
 * @since 1.1.0
 */
function doc_display_activation_error_notice(): void {
    $error_message = get_transient('doc_activation_error');
    if ($error_message) {
        delete_transient('doc_activation_error');
        if (function_exists('doc_admin_error_notice')) {
            doc_admin_error_notice($error_message);
        } else {
            printf(
                '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                wp_kses_post($error_message)
            );
        }
    }
}

