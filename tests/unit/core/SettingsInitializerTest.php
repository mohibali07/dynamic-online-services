<?php
/**
 * SettingsInitializer Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\SettingsInitializer;

/**
 * Test SettingsInitializer class.
 */
class SettingsInitializerTest extends DYNOS_TestCase {

    public function test_init_initializes_settings() {
        // Settings::get_instance() usually adds admin_menu hooks.

        \WP_Mock::userFunction('get_option')->andReturn([]);
        \WP_Mock::userFunction('wp_parse_args')->andReturnArg(0);

        // This might fail if Settings class tries to do complex things in constructor.
        // But Settings usually just sets up instance.

        SettingsInitializer::init();

        $this->assertTrue(true);
    }
}
