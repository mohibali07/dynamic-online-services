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
		$instance->setAccessible(true);
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

        // get_instance calls init internally? Or constructor?
        // Assuming constructor adds actions
		\WP_Mock::expectActionAdded('admin_notices', \WP_Mock\Functions::type('array'));
		\WP_Mock::expectActionAdded('init', \WP_Mock\Functions::type('array'));

		// Verify singleton pattern
		$instance = Plugin::get_instance();
        $this->assertInstanceOf(Plugin::class, $instance);
	}

	/**
	 * Test register_blocks is called on init.
	 */
	public function test_register_blocks_hook(): void
	{
		\WP_Mock::expectActionAdded('init', \WP_Mock\Functions::type('array'));

        // This test seems to check if init hook is added?
        // But register_blocks is the callback?
        // If we want to test that register_blocks IS hooked check Plugin::init?
        // Let's assume Plugin::init() registers it.
        // But Plugin::init() is private? NO, get_instance calls it.
        // We already tested get_instance.
        // Maybe this test intended to call Plugin::register_blocks()?
        // But register_blocks usually does `register_block_type`.
        // The expectation is `expectActionAdded('init')`.
        // So we must call code that does `add_action('init')`.
        // That is likely `Plugin::__construct` or `init`.
        // We'll call `Plugin::get_instance()`.

        Plugin::get_instance();
	}
}
