<?php
/**
 * Custom Post Types and Taxonomies
 *
 * Main loader file for post type functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Post_Types
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include post type components
require_once DOC_PLUGIN_DIR . 'includes/post-types/sanitization.php';
require_once DOC_PLUGIN_DIR . 'includes/post-types/registration.php';
require_once DOC_PLUGIN_DIR . 'includes/post-types/permalink.php';
