<?php
/**
 * Options Helper Functions
 *
 * Handles plugin options retrieval and management.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get plugin options with caching.
 *
 * Caching Strategy:
 * This function uses a three-layer caching approach to optimize performance:
 * 1. Static variable cache (request-level): Stores options in memory for the current request
 * 2. Transient cache version: Tracks cache validity (expires after 24 hours)
 * 3. Hash-based change detection: Compares MD5 hash of options to detect changes
 *
 * Cache Invalidation:
 * - Automatic: When options are updated via update_option/add_option/delete_option hooks
 * - Manual: Call doc_invalidate_options_cache() or pass $force_refresh = true
 * - Time-based: Cache version transient expires after 24 hours
 *
 * Performance Benefits:
 * - Reduces database queries by caching options in memory
 * - Prevents stale data with hash-based change detection
 * - Automatically refreshes when options are modified
 *
 * @since 1.1.0
 * @param bool $force_refresh Whether to force refresh the cache. Default false.
 * @return array Options array. Always returns an array, never false or null.
 */
function doc_get_options($force_refresh = false): array
{
    static $options = null;

    // Layer 1: Check if cache should be invalidated (transient-based)
    // If cache version transient doesn't exist, cache is stale
    $cache_version = get_transient('doc_options_cache_version');
    if (false === $cache_version) {
        $force_refresh = true;
    }

    // Layer 2: Hash-based change detection
    // Compare current options hash with cached hash to detect changes
    $last_update = get_transient('doc_options_last_update');
    if (false !== $last_update) {
        $current_options = get_option('doc_options', false);
        if (false !== $current_options) {
            $options_hash = md5(maybe_serialize($current_options));
            $cached_hash = get_transient('doc_options_hash');
            if ($cached_hash !== $options_hash) {
                $force_refresh = true;
                // Update cached hash
                set_transient('doc_options_hash', $options_hash, DAY_IN_SECONDS);
            }
        }
    }

    // Layer 3: Static variable cache (request-level)
    // Only fetch from database if cache is invalid or forced refresh
    if (null === $options || $force_refresh) {
        // Use centralized default options function
        $defaults = \DynamicOnlineServices\Settings\Defaults::get_options();
        $options = get_option('doc_options', $defaults);
        $options = wp_parse_args($options, $defaults);

        // Allow filtering options
        $options = apply_filters('doc_get_options', $options);

        // Update all cache layers
        set_transient('doc_options_cache_version', time(), DAY_IN_SECONDS);
        set_transient('doc_options_last_update', time(), DAY_IN_SECONDS);
        // Store hash for change detection
        set_transient('doc_options_hash', md5(maybe_serialize($options)), DAY_IN_SECONDS);
    }

    return $options;
}

/**
 * Invalidate options cache.
 *
 * @since 1.1.0
 * @return void
 */
function doc_invalidate_options_cache(): void
{
    delete_transient('doc_options_cache_version');
    delete_transient('doc_options_last_update');
    delete_transient('doc_options_hash');
    // Fire action for cache invalidation
    do_action('doc_options_cache_invalidated');
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
function doc_maybe_invalidate_options_cache($old_value, $value, $option): void
{
    if ('doc_options' === $option) {
        doc_invalidate_options_cache();
        // Update last update timestamp and hash
        set_transient('doc_options_last_update', time(), DAY_IN_SECONDS);
        if (is_array($value)) {
            set_transient('doc_options_hash', md5(maybe_serialize($value)), DAY_IN_SECONDS);
        }
    }
}
add_action('update_option', 'doc_maybe_invalidate_options_cache', 10, 3);

/**
 * Invalidate cache when options are added (for new installations).
 *
 * @since 1.1.0
 * @param string $option The option name.
 * @param mixed  $value  The option value.
 * @return void
 */
function doc_maybe_invalidate_options_cache_on_add($option, $value): void
{
    if ('doc_options' === $option) {
        doc_invalidate_options_cache();
        set_transient('doc_options_last_update', time(), DAY_IN_SECONDS);
        if (is_array($value)) {
            set_transient('doc_options_hash', md5(maybe_serialize($value)), DAY_IN_SECONDS);
        }
    }
}
add_action('add_option', 'doc_maybe_invalidate_options_cache_on_add', 10, 2);

/**
 * Invalidate cache when options are deleted.
 *
 * @since 1.1.0
 * @param string $option The option name.
 * @return void
 */
function doc_maybe_invalidate_options_cache_on_delete($option): void
{
    if ('doc_options' === $option) {
        doc_invalidate_options_cache();
        delete_transient('doc_options_last_update');
        delete_transient('doc_options_hash');
    }
}
add_action('delete_option', 'doc_maybe_invalidate_options_cache_on_delete', 10, 1);

/**
 * Get option value with fallback to default.
 *
 * @since 1.1.0
 * @param array  $options  Options array.
 * @param string $key      Option key.
 * @param mixed  $default  Default value if option not found.
 * @return mixed Option value or default.
 */
function doc_get_option($options, $key, $default = ''): mixed
{
    if (!is_array($options)) {
        return $default;
    }

    $value = isset($options[$key]) ? $options[$key] : $default;

    // Allow filtering the option value
    return apply_filters('doc_get_option', $value, $key, $default, $options);
}

