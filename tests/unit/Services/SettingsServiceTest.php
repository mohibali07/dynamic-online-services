<?php
/**
 * Settings Service Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Services;

use TechmireSolutions\DynamicOnlineServices\Services\SettingsService;
use WP_Mock;
use DYNOS_TestCase;

class SettingsServiceTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
        // Reset singleton instance
        $reflection = new \ReflectionClass(\TechmireSolutions\DynamicOnlineServices\Services\SettingsService::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, null);
    }

    public function test_get_option() {
        $key = 'some_option';
        $default = 'default';
        $value = 'value';

        // Mock namespaced get_option to be sure
        WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Helpers\get_option', [
             'return' => ['some_option' => 'value']
        ]);
        WP_Mock::userFunction('get_option', [
             'return' => ['some_option' => 'value']
        ]);

        WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Helpers\get_transient', [
             'return' => false
        ]);
        WP_Mock::userFunction('get_transient', [
             'return' => false
        ]);

        WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Helpers\wp_parse_args', [
             'return' => function($args, $defaults) { return array_merge($defaults, $args); }
        ]);
        WP_Mock::userFunction('set_transient', [
            'return' => true
        ]);



        $service = SettingsService::get_instance();
        $result = $service->get_option($key, $default);

        $this->assertEquals($value, $result);
    }
}
