<?php
/**
 * Options Helper Class
 *
 * Handles plugin options retrieval and management.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Options
 *
 * Replaces global functions for option handling.
 */
class Options
{
    /**
     * Initialize the options helper.
     * Registers necessary hooks for cache invalidation.
     *
     * @since 1.1.0
     * @return void
     */
    public static function init(): void
    {
        add_action('update_option', array(self::class, 'maybe_invalidate_cache'), 10, 3);
        add_action('add_option', array(self::class, 'maybe_invalidate_cache_on_add'), 10, 2);
        add_action('delete_option', array(self::class, 'maybe_invalidate_cache_on_delete'), 10, 1);
    }

    /**
     * Internal cache for options.
     *
     * @var array|null
     */
    private static $options_cache = null;

    /**
     * Get plugin options with caching.
     *
     * @since 1.1.0
     * @param bool $force_refresh Whether to force refresh the cache. Default false.
     * @return array Options array. Always returns an array, never false or null.
     */
    public static function get(bool $force_refresh = false): array
    {
        // Layer 1: Check if cache should be invalidated (transient-based)
        $cache_version = get_transient('dynos_options_cache_version');
        if (false === $cache_version) {
            $force_refresh = true;
        }

        if (!$force_refresh && null !== self::$options_cache) {
            return self::$options_cache;
        }

        // Layer 2: Hash-based change detection (only if not already forcing)
        if (!$force_refresh) {
            $last_update = get_transient('dynos_options_last_update');
            if (false !== $last_update) {
                $current_options = get_option('dynos_options', false);
                if (false !== $current_options) {
                    // Use wp_json_encode for safe hashing (WPCS compliant)
                    $options_hash = md5( (string) wp_json_encode( $current_options ) );
                    $cached_hash = get_transient('dynos_options_hash');
                if ($cached_hash !== $options_hash) {
                    $force_refresh = true;
                    set_transient('dynos_options_hash', $options_hash, DAY_IN_SECONDS);
                }
            }
        }
        }

        // Layer 3: Static variable cache (request-level)
        if (null === self::$options_cache || $force_refresh) {
            // Use centralized default options function
            $defaults = \TechmireSolutions\DynamicOnlineServices\Settings\Defaults::get_options();
            $stored_options = get_option('dynos_options', $defaults);

            // Ensure $stored_options is an array
            if (!is_array($stored_options)) {
                $stored_options = $defaults;
            }

            self::$options_cache = wp_parse_args($stored_options, $defaults);
            self::$options_cache = apply_filters('dynos_get_options', self::$options_cache);

            set_transient('dynos_options_cache_version', time(), DAY_IN_SECONDS);
            set_transient('dynos_options_last_update', time(), DAY_IN_SECONDS);
            set_transient('dynos_options_hash', md5( (string) wp_json_encode( self::$options_cache ) ), DAY_IN_SECONDS);
        }

        return (array)self::$options_cache;
    }

    /**
     * Invalidate options cache.
     *
     * @since 1.1.0
     * @return void
     */
    public static function invalidate_cache(): void
    {
        delete_transient('dynos_options_cache_version');
        delete_transient('dynos_options_last_update');
        delete_transient('dynos_options_hash');
        do_action('dynos_options_cache_invalidated');
    }

    /**
     * Invalidate cache when options are updated.
     *
     * @since 1.1.0
     * @param mixed  $old_value The old option value.
     * @param mixed  $value     The new option value.
     * @param string $option    The option name.
     * @return void
     */
    public static function maybe_invalidate_cache($old_value, $value, $option): void
    {
        if ('dynos_options' === $option) {
            self::invalidate_cache();
            set_transient('dynos_options_last_update', time(), DAY_IN_SECONDS);
            if (is_array($value)) {
                // Use wp_json_encode for safe hashing (WPCS compliant)
                set_transient('dynos_options_hash', md5( (string) wp_json_encode( $value ) ), DAY_IN_SECONDS);
            }
        }
    }

    /**
     * Invalidate cache when options are added.
     *
     * @since 1.1.0
     * @param string $option The option name.
     * @param mixed  $value  The option value.
     * @return void
     */
    public static function maybe_invalidate_cache_on_add($option, $value): void
    {
        if ('dynos_options' === $option) {
            self::invalidate_cache();
            set_transient('dynos_options_last_update', time(), DAY_IN_SECONDS);
            if (is_array($value)) {
                // Use wp_json_encode for safe hashing (WPCS compliant)
                set_transient('dynos_options_hash', md5( (string) wp_json_encode( $value ) ), DAY_IN_SECONDS);
            }
        }
    }

    /**
     * Invalidate cache when options are deleted.
     *
     * @since 1.1.0
     * @param string $option The option name.
     * @return void
     */
    public static function maybe_invalidate_cache_on_delete($option): void
    {
        if ('dynos_options' === $option) {
            self::invalidate_cache();
            delete_transient('dynos_options_last_update');
            delete_transient('dynos_options_hash');
        }
    }

    /**
     * Get option value with fallback to default.
     *
     * @since 1.1.0
     * @param array  $options  Options array.
     * @param string $key      Option key.
     * @param mixed  $default  Default value if option not found.
     * @return mixed Option value or default.
     */
    public static function get_option($options, $key, $default = ''): mixed
    {
        if (!is_array($options)) {
            return $default;
        }

        $value = isset($options[$key]) ? $options[$key] : $default;

        return apply_filters('dynos_get_option', $value, $key, $default, $options);
    }

    /**
     * Reset the options cache.
     * Useful for testing.
     *
     * @since 1.1.0
     * @return void
     */
    public static function reset_cache(): void
    {
        self::$options_cache = null;
    }
}
