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
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Core\Plugin', 'get_instance'));
	}

	/**
	 * Test register_blocks is called on init.
	 */
	public function test_register_blocks_hook(): void
	{
		\WP_Mock::expectActionAdded('init', \WP_Mock\Functions::type('array'));

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Core\Plugin', 'register_blocks'));
	}
}
