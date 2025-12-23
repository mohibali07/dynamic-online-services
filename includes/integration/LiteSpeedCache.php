<?php
/**
 * LiteSpeed Cache Integration
 *
 * @package Dynamic_Online_Services
 * @subpackage Integration
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Integration;

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
        if (!defined('LSCWP_V')) {
            return; // LiteSpeed Cache not active
        }

        // Hook into settings save to purge cache when global styles/settings change.
        add_action('updated_option', array(__CLASS__, 'on_option_update'), 10, 3);

        add_action('dynos_after_save_faqs', [self::class, 'purge_post_cache']);
        add_action('save_post', [self::class, 'purge_post_cache']);
        add_action('created_term', [self::class, 'purge_term_cache']);
        add_action('edited_term', [self::class, 'purge_term_cache']);
        add_action('delete_term', [self::class, 'purge_term_cache']);

        // Add cache tags for better granular purging
        add_filter('litespeed_cache_tag_prefix', [self::class, 'add_cache_tag_prefix']);
        add_action('wp_head', [self::class, 'add_public_cache_tags'], 1);
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
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party hook
        if (has_action('litespeed_purge_all')) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party hook
            do_action('litespeed_purge_all');
            return;
        }

        // Method 2: Check for global function if action not available (older versions or direct call)
        if (function_exists('litespeed_purge_all')) {
            litespeed_purge_all();
        }
    }

    /**
     * Purge post cache when post is saved.
     *
     * @param int $post_id Post ID.
     */
    public static function purge_post_cache(int $post_id): void
    {
        if (function_exists('do_action')) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party hook
            do_action('litespeed_purge_post', $post_id);
        }
    }

    /**
     * Purge term cache.
     *
     * @param int $term_id Term ID.
     */
    public static function purge_term_cache(int $term_id): void
    {
        if (function_exists('do_action')) {
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party hook
            do_action('litespeed_purge_all');
        }
    }

    /**
     * Add cache tag prefix for plugin-specific tags.
     *
     * @param string $prefix Current prefix.
     * @return string Modified prefix.
     */
    public static function add_cache_tag_prefix(string $prefix): string
    {
        return $prefix . '.dynos';
    }

    /**
     * Add public cache tags for better granular purging.
     * Allows purging specific service or category pages without full purge.
     */
    public static function add_public_cache_tags(): void
    {
        if (!function_exists('do_action')) {
            return;
        }

        // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party hooks
        // Add tag for service post type pages
        if (is_singular('service')) {
            $post_id = get_the_ID();
            do_action('litespeed_tag_add', 'dynos_service_' . $post_id);
        }

        // Add tag for service category archive pages
        if (is_tax('service-category')) {
            $term = get_queried_object();
            if ($term && isset($term->term_id)) {
                do_action('litespeed_tag_add', 'dynos_cat_' . $term->term_id);
            }
        }

        // Add general tag for all plugin pages
        do_action('litespeed_tag_add', 'dynos_all');
        // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }
}
