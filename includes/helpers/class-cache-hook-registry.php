<?php
/**
 * Cache Hook Registry Class
 *
 * Responsible for registering WordPress hooks for cache invalidation.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * CacheHookRegistry class.
 *
 * Single responsibility: Register WordPress hooks for cache management.
 *
 * @since 1.1.3
 */
class CacheHookRegistry
{

	/**
	 * Initialize cache-related hooks.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function init(): void
	{
		add_action('save_post', [Cache::class, 'clear_on_post_save']);
		add_action('created_term', [Cache::class, 'clear_on_term_save']);
		add_action('edited_term', [Cache::class, 'clear_on_term_save']);
		add_action('delete_term', [Cache::class, 'clear_on_term_save']);
		add_action('dynos_options_cache_invalidated', [Cache::class, 'clear_on_settings_update']);
	}
}
