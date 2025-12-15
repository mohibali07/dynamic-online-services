<?php
/**
 * PHPUnit Bootstrap
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir() . '/wordpress/');
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define plugin constants
if (!defined('DYNOS_PLUGIN_DIR')) {
    define('DYNOS_PLUGIN_DIR', dirname(dirname(__FILE__)) . '/');
}

// Manually require the Autoloader since we're not loading the main plugin file entirely
require_once DYNOS_PLUGIN_DIR . 'includes/class-autoloader.php';
\TechmireSolutions\DynamicOnlineServices\Autoloader::run();

// Mock WordPress functions if not available
if (!function_exists('add_action')) {
    function add_action()
    {
    }
}
if (!function_exists('add_filter')) {
    function add_filter()
    {
    }
}
if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file)
    {
        return dirname($file) . '/';
    }
}
if (!function_exists('esc_html')) {
    function esc_html($text)
    {
        return $text;
    }
}
if (!function_exists('__')) {
    function __($text, $domain)
    {
        return $text;
    }
}
