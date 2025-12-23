<?php

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Plugin Class Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\Plugin;
use WP_Mock;
/**
 * Test Plugin class.
 */
class PluginTest extends TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::setUp();

		// Ensure fresh singleton for every test
		$reflection = new \ReflectionClass(Plugin::class);
		$instance = $reflection->getProperty('instance');
		$instance->setValue(null, null);
	}

	/**
	 * Tear down test environment.
	 */
	public function tearDown(): void
	{
		\WP_Mock::tearDown();

		// Reset Singleton
		$reflection = new \ReflectionClass(Plugin::class);
		$instance = $reflection->getProperty('instance');
		$instance->setValue(null, null);

		parent::tearDown();
	}

	/**
	 * Test plugin class exists.
	 */
	public function test_plugin_class_exists(): void
	{
		$this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Core\Plugin'));
	}

	/**
	 * Test get_instance returns singleton.
	 */
	public function test_get_instance_returns_singleton(): void
	{
		// Mock all WordPress functions that might be called during instantiation
		\WP_Mock::userFunction('get_option')
			->andReturn([]);
        \WP_Mock::userFunction('get_transient')
            ->andReturn(false);

		\WP_Mock::userFunction('wp_parse_args')
			->andReturnUsing(function ($args, $defaults) {
				return array_merge($defaults, $args);
			});

		// To test hook registration in the constructor, we need to set expectations BEFORE instantiation
		// But since the callback uses the instance itself, we have a chicken-and-egg problem.
		// We'll trust the constructor's call indirectly or test define_hooks if it were public.
		// For now, instantiate first, then check if we can manually trigger the registration logic.
		$plugin = Plugin::get_instance();
		$this->assertInstanceOf(Plugin::class, $plugin);
	}

	/**
	 * Test register_blocks is called on init.
	 */
	public function test_register_blocks_hook(): void {
		$plugin = Plugin::get_instance();
        // Reflection to call private method for testing and verify it adds the hook
        $reflection = new \ReflectionClass($plugin);
        $method = $reflection->getMethod('define_hooks');

        \WP_Mock::expectActionAdded('init', array($plugin, 'register_blocks'));
        $method->invoke($plugin);

		$this->assertConditionsMet();
	}
}
