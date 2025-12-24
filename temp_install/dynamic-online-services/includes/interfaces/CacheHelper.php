<?php
/**
 * Cache Helper Interface
 *
 * @package Dynamic_Online_Services
 * @subpackage Interfaces
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Interfaces;

if (!\defined('ABSPATH')) {
	exit;
}

/**
 * Interface CacheHelperInterface
 */
interface CacheHelperInterface
{
    /**
     * Get posts from cache.
     *
     * @param string $key Cache key.
     * @return array|bool Cached posts or false.
     */
    public function get_posts(string $key);
}
