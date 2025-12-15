<?php
/**
 * Admin Scripts and Styles
 *
 * Main loader for admin scripts and styles.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include admin script components
require_once DYNOS_PLUGIN_DIR . 'includes/admin/scripts-taxonomy.php';
require_once DYNOS_PLUGIN_DIR . 'includes/admin/scripts-settings.php';
