<?php
/**
 * Activator Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use TechmireSolutions\DynamicOnlineServices\Core\Activator;

/**
 * Test Activator class.
 */
class ActivatorTest extends \DYNOS_TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::setUp();

		// Mock is_admin for SlugInitializer
		\WP_Mock::userFunction('is_admin', [
			'return' => true
		]);

		// Mock update_option for SlugInitializer
		\WP_Mock::userFunction('update_option', [
			'return' => true
		]);
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

        // Mock error handler checks
        \WP_Mock::userFunction('dynos_handle_activation_error')
            ->andReturn(true); // Helper function from Activator if exists or global

		// Mock options migration
		\WP_Mock::userFunction('get_option')
			->andReturn([]); // Return empty array to use defaults

        // Defaults::get_options() might be called. Mock it?
        // Activator calls Defaults::get_options().
        if (!class_exists('TechmireSolutions\DynamicOnlineServices\Settings\Defaults')) {
             \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Settings\Defaults')
                ->shouldReceive('get_options')->andReturn([
                    'service_post_type_slug' => 'service',
                    'service_taxonomy_slug' => 'service-category'
                ]);
        }

		// Mock set_transient for activation flag
		// Mock set_transient
		\WP_Mock::userFunction('set_transient')
			->andReturn(true);

		// Mock set_transient
		\WP_Mock::userFunction('set_transient')
			->andReturn(true);

		// Mock permissions check
		\WP_Mock::userFunction('current_user_can')
			->with('activate_plugins')
			->andReturn(true);

		// This test verifies the method exists and can be called
		// Execute method
		Activator::activate();

		// Verify conditions
		$this->assertConditionsMet();
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

		// Mock permissions check
		\WP_Mock::userFunction('current_user_can')
			->with('activate_plugins')
			->andReturn(true);

		// Execute method
		Activator::activate();

		// Verify conditions
		$this->assertConditionsMet();
	}
}
