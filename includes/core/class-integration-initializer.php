<?php
/**
 * Integration Initializer Class
 *
 * Handles initialization of plugin integrations.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

use TechmireSolutions\DynamicOnlineServices\Integration\LiteSpeedCache;

class IntegrationInitializer
{
    /**
     * Initialize integrations.
     */
    public static function init(): void
    {
        LiteSpeedCache::init();
    }
}
?>
