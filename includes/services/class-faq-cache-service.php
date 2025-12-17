<?php
/**
 * FAQ Cache Service Class
 *
 * Responsible for caching FAQ data.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

use TechmireSolutions\DynamicOnlineServices\Helpers\Cache;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * FaqCacheService class.
 *
 * Single responsibility: Cache FAQ post meta data.
 *
 * @since 1.1.3
 */
class FaqCacheService
{

	/**
	 * Get cached FAQs for a post.
	 *
	 * @since 1.1.3
	 * @param int $post_id Post ID.
	 * @return array FAQ data.
	 */
	public static function get(int $post_id): array
	{
		return Cache::get_or_set(
			'dynos_faqs_' . $post_id,
			function () use ($post_id) {
				$faqs = get_post_meta($post_id, 'dynos_faqs', true);
				return is_array($faqs) ? $faqs : [];
			},
			DAY_IN_SECONDS
		);
	}

	/**
	 * Clear FAQ cache for a specific post.
	 *
	 * @since 1.1.3
	 * @param int $post_id Post ID.
	 * @return bool True on success.
	 */
	public static function clear(int $post_id): bool
	{
		return delete_transient('dynos_faqs_' . $post_id);
	}

	/**
	 * Clear all FAQ caches.
	 *
	 * @since 1.1.3
	 * @return int Number of caches cleared.
	 */
	public static function clear_all(): int
	{
		return Cache::clear('dynos_faqs_*');
	}
}
