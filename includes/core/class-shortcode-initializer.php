<?php
/**
 * Shortcode Initializer Class
 *
 * Handles initialization of all shortcodes.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

use TechmireSolutions\DynamicOnlineServices\Shortcodes\Category;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards;

class ShortcodeInitializer
{
    /**
     * Initialize all shortcodes.
     */
    public static function init(): void
    {
        Category::init();
        Faqs::init();
        Hero::init();
        Cards::init();
    }
}
?>
