<?php
declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Activator Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

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
		\WP_Mock::userFunction('get_option')
			->andReturn(false);

		\WP_Mock::userFunction('add_option')
			->once()
			->andReturn(true);

		\WP_Mock::userFunction('deactivate_plugins')
			->never();

        // Stub/Mock requirements check logic (which is private/internal PHP check)
        // Since check_requirements is private and checks PHP version (true), we don't mock it directly.
        // It does NOT call dynos_check_requirements globally.
        // So remove that expectation.

		\WP_Mock::userFunction('set_transient')
			->once()
			->with('dynos_plugin_activated', true, 30)
			->andReturn(true);

         // Mock global vars for check_requirements
         global $wp_version;
         $wp_version = '6.0';

		// Mock transients called by Options::get() which is called via register_post_types_and_flush -> ServicePostType -> get_labels
		\WP_Mock::userFunction('get_transient')
			->andReturn(false);
		\WP_Mock::userFunction('set_transient')
			->with('dynos_options_cache_version', \WP_Mock\Functions::type('int'), 86400)
			->andReturn(true);
		\WP_Mock::userFunction('set_transient')
			->with('dynos_options_last_update', \WP_Mock\Functions::type('int'), 86400)
			->andReturn(true);
		\WP_Mock::userFunction('set_transient')
			->with('dynos_options_hash', \WP_Mock\Functions::type('string'), 86400)
			->andReturn(true);

		// Actually call the method
		Activator::activate();

		$this->assertTrue(method_exists(Activator::class, 'activate'));
	}

	/**
	 * Test activate method handles requirements failure.
	 */
	public function test_activate_handles_requirements_failure(): void
	{
		\WP_Mock::userFunction('get_option')
			->andReturn(false);

		\WP_Mock::userFunction('add_option')
			->once()
			->andReturn(true);

        // To simulate failure, we need check_requirements to return false.
        // But check_requirements is private and checks constants/globals.
        // We can mock $wp_version to be old.
        global $wp_version;
        $wp_version = '4.0';

		\WP_Mock::userFunction('deactivate_plugins')
			->once();

		\WP_Mock::userFunction('set_transient')
			->with('dynos_activation_error', \WP_Mock\Functions::type('string'), 30)
			->once()
			->andReturn(true);

		// Actually call the method
		Activator::activate();

		// Verify method exists
		$this->assertTrue(method_exists(Activator::class, 'activate'));
	}
}
