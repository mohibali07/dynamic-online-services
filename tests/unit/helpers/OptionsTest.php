<?php
/**
 * Options Helper Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Helpers;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Helpers\Options;

/**
 * Test Options helper class.
 *
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class OptionsTest extends \DYNOS_TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
        require_once __DIR__ . '/../../namespaced-stubs.php';
        \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::reset();
        Options::reset_static_cache();
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
	 * Test get options with cache hit.
	 */
	public function test_get_options_with_cache(): void
	{
		// Mock dependencies
		$defaults = ['default' => 'value'];
		\WP_Mock::userFunction('get_transient')
			->with('dynos_options_cache_version')
			->andReturn(time());

		\WP_Mock::userFunction('get_transient')
			->with('dynos_options_last_update')
			->andReturn(false);

		\WP_Mock::userFunction('get_option')
			->withAnyArgs() // Keep relaxed args or revert to specific args if confident
			->andReturn(['test_key' => 'test_value']);


		// wp_parse_args is handled by stub logic + MockSpy

		\WP_Mock::onFilter('dynos_get_options')
			->with(\WP_Mock\Functions::type('array'))
			->reply(function ($options) {
				return $options;
			});

		\WP_Mock::userFunction('set_transient')->andReturn(true);

        // maybe_serialize interacts with MockSpy but we don't need to mock it as stub handles it logic.

		$options = Options::get();

		$this->assertIsArray($options);
        // set_transient calls: cache_version, last_update, hash
        $this->assertEquals(3, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('set_transient'));
	}

	/**
	 * Test cache invalidation.
	 */
	public function test_invalidate_cache(): void
	{
		\WP_Mock::userFunction('delete_transient')->andReturn(true);

		\WP_Mock::expectAction('dynos_options_cache_invalidated');

		Options::invalidate_cache();

		$this->assertConditionsMet();
        $this->assertEquals(3, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('delete_transient'));
	}

	/**
	 * Test maybe invalidate cache on update.
	 */
	public function test_maybe_invalidate_cache_on_update(): void
	{
		$old_value = ['old' => 'value'];
		$new_value = ['new' => 'value'];

		\WP_Mock::userFunction('delete_transient')->andReturn(true);

		\WP_Mock::expectAction('dynos_options_cache_invalidated');

		\WP_Mock::userFunction('set_transient')->andReturn(true);

        // maybe_serialize stubbed in namespaced-stubs.php

		Options::maybe_invalidate_cache($old_value, $new_value, 'dynos_options');

		$this->assertConditionsMet();
        $this->assertEquals(3, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('delete_transient'));
        $this->assertEquals(2, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('set_transient'));
	}

	/**
	 * Test maybe invalidate cache ignores other options.
	 */
	public function test_maybe_invalidate_cache_ignores_other_options(): void
	{
		// Should not call delete_transient for non-plugin options

		Options::maybe_invalidate_cache([], [], 'other_option');

		$this->assertConditionsMet();
        $this->assertEquals(0, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('delete_transient'));
	}

	/**
	 * Test get option with existing key.
	 */
	public function test_get_option_existing_key(): void
	{
		$options = ['key1' => 'value1', 'key2' => 'value2'];

		\WP_Mock::onFilter('dynos_get_option')
			->with('value1', 'key1', '', $options)
			->reply('value1');

		$result = Options::get_option($options, 'key1');

		$this->assertEquals('value1', $result);
	}

	/**
	 * Test get option with missing key returns default.
	 */
	public function test_get_option_missing_key_returns_default(): void
	{
		$options = ['key1' => 'value1'];
		$default = 'default_value';

		\WP_Mock::onFilter('dynos_get_option')
			->with($default, 'missing_key', $default, $options)
			->reply($default);

		$result = Options::get_option($options, 'missing_key', $default);

		$this->assertEquals($default, $result);
	}

	/**
	 * Test get option with non-array returns default.
	 */
	public function test_get_option_non_array_returns_default(): void
	{
		$default = 'default_value';

		$result = Options::get_option('not_an_array', 'key', $default);

		$this->assertEquals($default, $result);
	}

	/**
	 * Test init registers hooks.
	 */
	public function test_init_registers_hooks(): void
	{
		\WP_Mock::expectActionAdded('update_option', [Options::class, 'maybe_invalidate_cache'], 10, 3);
		\WP_Mock::expectActionAdded('add_option', [Options::class, 'maybe_invalidate_cache_on_add'], 10, 2);
		\WP_Mock::expectActionAdded('delete_option', [Options::class, 'maybe_invalidate_cache_on_delete'], 10, 1);

		Options::init();

		$this->assertConditionsMet();
	}
}
