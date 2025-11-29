<?php
/**
 * Taxonomy Custom Fields
 *
 * Main loader for taxonomy custom fields functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomy_Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include taxonomy field components
require_once DOC_PLUGIN_DIR . 'includes/taxonomy/fields-renderer.php';
require_once DOC_PLUGIN_DIR . 'includes/taxonomy/fields-saver.php';

