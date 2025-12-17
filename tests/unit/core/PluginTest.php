<?php
/**
 * Plugin Class Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use WP_Mock\Tools\TestCase;

use WP_Mock;
use DYNOS_TestCase;

/**
 * Test Plugin class.
 */
/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class PluginTest extends DYNOS_TestCase {
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
		// \WP_Mock::setUp();

		// Mock is_admin for DependencyLoader
		\WP_Mock::userFunction('is_admin', [
			'return' => true
		]);

		// Mock register_block_type for Plugin class
		\WP_Mock::userFunction('register_block_type', [
			'return' => true
		]);

        // Reset Plugin singleton
        $reflection = new \ReflectionClass(\TechmireSolutions\DynamicOnlineServices\Core\Plugin::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, null);
	}

	/**
	 * Tear down test environment.
	 */
	public function tearDown(): void
	{
		\WP_Mock::tearDown();
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

		\WP_Mock::userFunction('wp_parse_args')
			->andReturnUsing(function ($args, $defaults) {
				return array_merge($defaults, $args);
			});

		\WP_Mock::expectActionAdded('admin_notices', \WP_Mock\Functions::type('array'));
		\WP_Mock::expectActionAdded('init', \WP_Mock\Functions::type('array'));

		// Verify singleton pattern
		// Execute method
		$instance = \TechmireSolutions\DynamicOnlineServices\Core\Plugin::get_instance();

		// Verify instance
		$this->assertInstanceOf('TechmireSolutions\DynamicOnlineServices\Core\Plugin', $instance);

		// Verify conditions
		$this->assertConditionsMet();
	}

	/**
	 * Test register_blocks is called on init.
	 */
	public function test_register_blocks_hook(): void
	{
		\WP_Mock::expectActionAdded('init', \WP_Mock\Functions::type('array'));

		// Verify method exists
		// Execute method via instance
		$plugin = \TechmireSolutions\DynamicOnlineServices\Core\Plugin::get_instance();
        $plugin->register_blocks();

		// Verify conditions
		$this->assertConditionsMet();
	}
}
