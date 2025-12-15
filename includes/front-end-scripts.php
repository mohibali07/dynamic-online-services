<?php
/**
 * Front-end Scripts and Styles
 *
 * This file now acts as a loader for front-end styles.
 *
 * @package Dynamic_Online_Services
 * @subpackage Front_End
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include front-end style files
require_once DYNOS_PLUGIN_DIR . 'includes/styles/card-styles.php';
require_once DYNOS_PLUGIN_DIR . 'includes/styles/faq-styles.php';
require_once DYNOS_PLUGIN_DIR . 'includes/styles/hero-styles.php';
