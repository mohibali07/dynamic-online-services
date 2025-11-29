<?php
/**
 * Settings Renderer
 *
 * Handles rendering of settings page and field callbacks.
 * This file now acts as a loader for the split settings components.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include settings components
require_once DOC_PLUGIN_DIR . 'includes/settings/defaults.php';
require_once DOC_PLUGIN_DIR . 'includes/settings/sanitization.php';
require_once DOC_PLUGIN_DIR . 'includes/settings/field-renderers.php';
require_once DOC_PLUGIN_DIR . 'includes/settings/page-renderer.php';
