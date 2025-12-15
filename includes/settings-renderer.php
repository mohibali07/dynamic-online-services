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

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include settings components
require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-config.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-defaults.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-sanitization.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-field-renderers.php';
require_once DYNOS_PLUGIN_DIR . 'includes/settings/class-page-renderer.php';
