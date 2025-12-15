<?php
/**
 * Helper Functions
 *
 * This file now acts as a loader for helper functions.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

// Include helper files
// require_once DYNOS_PLUGIN_DIR . 'includes/helpers/options.php';
// require_once DYNOS_PLUGIN_DIR . 'includes/helpers/fonts.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/images.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/acf.php';
// require_once DYNOS_PLUGIN_DIR . 'includes/helpers/sanitization.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/admin-notices.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/screen.php';
// require_once DYNOS_PLUGIN_DIR . 'includes/helpers/style-builder.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/asset-enqueuer.php';
require_once DYNOS_PLUGIN_DIR . 'includes/helpers/shortcode-attributes.php';
