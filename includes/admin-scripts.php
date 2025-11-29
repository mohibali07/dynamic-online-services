<?php
/**
 * Admin Scripts and Styles
 *
 * Main loader for admin scripts and styles.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include admin script components
require_once DOC_PLUGIN_DIR . 'includes/admin/scripts-taxonomy.php';
require_once DOC_PLUGIN_DIR . 'includes/admin/scripts-settings.php';
