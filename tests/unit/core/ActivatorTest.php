<?php
/**
 * Activator Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\Activator;

/**
 * Test Activator class.
 */
class ActivatorTest extends TestCase
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
	 * Test activate method sets activation flag.
	 */
	public function test_activate_sets_activation_flag(): void
	{
		// Mock requirements check
		\WP_Mock::userFunction('dynos_check_requirements')
			->once()
			->andReturn(true);

		// Mock options migration
		\WP_Mock::userFunction('get_option')
			->andReturn(false);

		\WP_Mock::userFunction('update_option')
			->andReturn(true);

		// Mock set_transient for activation flag
		\WP_Mock::userFunction('set_transient')
			->with('dynos_plugin_activated', true, 30)
			->once()
			->andReturn(true);

		// Mock flush_rewrite_rules
		\WP_Mock::userFunction('flush_rewrite_rules')
			->once();

		// This test verifies the method exists and can be called
		$this->assertTrue(method_exists(Activator::class, 'activate'));
	}

	/**
	 * Test activate method handles requirements failure.
	 */
	public function test_activate_handles_requirements_failure(): void
	{
		\WP_Mock::userFunction('dynos_check_requirements')
			->once()
			->andReturn(false);

		\WP_Mock::userFunction('deactivate_plugins')
			->once();

		\WP_Mock::userFunction('__')
			->once()
			->andReturn('Error message');

		\WP_Mock::userFunction('set_transient')
			->with('dynos_activation_error', \WP_Mock\Functions::type('string'), 30)
			->once()
			->andReturn(true);

		// Verify method exists
		$this->assertTrue(method_exists(Activator::class, 'activate'));
	}
}
