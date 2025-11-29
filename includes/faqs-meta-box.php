<?php
/**
 * FAQs Meta Box
 *
 * Main loader for FAQ meta box functionality.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include FAQ components
require_once DOC_PLUGIN_DIR . 'includes/faqs/scripts.php';
require_once DOC_PLUGIN_DIR . 'includes/faqs/meta-box.php';
require_once DOC_PLUGIN_DIR . 'includes/faqs/saver.php';

