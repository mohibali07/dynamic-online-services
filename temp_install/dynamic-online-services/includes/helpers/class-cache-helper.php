<?php
/**
 * Cache Helper Implementation
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if (!\defined('ABSPATH')) {
	exit;
}

use TechmireSolutions\DynamicOnlineServices\Interfaces\CacheHelperInterface;

/**
 * Class CacheHelper
 */
class CacheHelper implements CacheHelperInterface
{
    /**
     * Get posts from cache.
     *
     * @param string $key Cache key.
     * @return array|bool Cached posts or false.
     */
    public function get_posts(string $key)
    {
        return get_transient($key);
    }
}
