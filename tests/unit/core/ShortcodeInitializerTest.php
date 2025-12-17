<?php
/**
 * ShortcodeInitializer Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\ShortcodeInitializer;

/**
 * Test ShortcodeInitializer class.
 */
class ShortcodeInitializerTest extends DYNOS_TestCase {

    public function test_init_registers_shortcodes() {
        // Expect add_shortcode calls
        \WP_Mock::userFunction('add_shortcode')->atLeast()->times(1);

        ShortcodeInitializer::init();

        $this->assertConditionsMet();
    }
}
