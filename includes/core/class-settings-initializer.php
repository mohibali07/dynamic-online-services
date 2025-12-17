<?php
/**
 * Settings Initializer Class
 *
 * Handles initialization of plugin settings.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

use TechmireSolutions\DynamicOnlineServices\Admin\Settings;

class SettingsInitializer
{
    /**
     * Initialize settings.
     */
    public static function init(): void
    {
        Settings::get_instance();
    }
}
?>
