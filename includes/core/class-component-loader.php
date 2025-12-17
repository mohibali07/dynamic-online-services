<?php
/**
 * Component Loader Class
 *
 * Handles loading of frontend and admin assets.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

class ComponentLoader
{
    /**
     * Initialize assets.
     */
    public static function init(): void
    {
        // Frontend assets
        $frontend = new FrontendAssets();
        $frontend->init();

        // Admin assets
        $admin = new \TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets();
        $admin->init();
    }
}
?>
