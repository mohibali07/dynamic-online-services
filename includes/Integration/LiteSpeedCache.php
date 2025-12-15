<?php
/**
 * LiteSpeed Cache Integration
 *
 * @package Dynamic_Online_Services
 * @subpackage Integration
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Integration;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * LiteSpeedCache class.
 */
class LiteSpeedCache
{

    /**
     * Initialize integration.
     */
    public static function init(): void
    {
        // Hook into settings save to purge cache when global styles/settings change.
        add_action('updated_option', array(__CLASS__, 'on_option_update'), 10, 3);
    }

    /**
     * Handle option updates.
     *
     * @param string $option    Option name.
     * @param mixed  $old_value Old value.
     * @param mixed  $value     New value.
     */
    public static function on_option_update(string $option, $old_value, $value): void
    {
        if ('dynos_options' === $option) {
            self::purge_all();
        }
    }

    /**
     * Purge all LiteSpeed Cache.
     * 
     * @return void
     */
    private static function purge_all(): void
    {
        // Method 1: LiteSpeed Cache Plugin API action
        if (has_action('litespeed_purge_all')) {
            do_action('litespeed_purge_all');
            return;
        }

        // Method 2: Check for global function if action not available (older versions or direct call)
        if (function_exists('litespeed_purge_all')) {
            litespeed_purge_all();
        }
    }
}
