<?php
/**
 * FAQ Initializer Class
 *
 * Handles initialization of FAQ meta box and saver.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

use TechmireSolutions\DynamicOnlineServices\FAQs\MetaBox;
use TechmireSolutions\DynamicOnlineServices\FAQs\Saver;

class FaqInitializer
{
    /**
     * Initialize FAQ components.
     */
    public static function init(): void
    {
        MetaBox::init();
        Saver::init();
    }
}
?>
