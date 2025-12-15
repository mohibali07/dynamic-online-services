<?php
/**
 * Taxonomy Custom Fields
 *
 * Main loader for taxonomy custom fields functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomy_Fields
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include taxonomy field components
require_once DYNOS_PLUGIN_DIR . 'includes/taxonomies/taxonomy/fields-renderer.php';
require_once DYNOS_PLUGIN_DIR . 'includes/taxonomies/taxonomy/fields-saver.php';
