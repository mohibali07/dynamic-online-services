<?php
/**
 * Cache Helper Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Helpers;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Helpers\Cache;

/**
 * Test Cache helper class.
 *
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class CacheTest extends \DYNOS_TestCase
{
	/**
	 * Set up test environment.
	 */

	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::setUp();
        require_once __DIR__ . '/../../namespaced-stubs.php';
        // Class is autoloaded

        if (!function_exists('TechmireSolutions\DynamicOnlineServices\Helpers\wp_using_ext_object_cache')) {
            \WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Helpers\wp_using_ext_object_cache', [
                'return' => false,
            ]);
        }
        \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::reset();
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
	 * Test cache hit scenario.
	 */
	public function test_cache_get_or_set_cache_hit(): void
	{
		$key = 'test_key';
		$cached_value = 'cached_data';

		\WP_Mock::userFunction('get_transient')
			->withAnyArgs()
			->andReturn($cached_value);

		// Callback should NOT be called on cache hit
		$callback = function () {
			$this->fail('Callback should not be called on cache hit');
		};

		$result = Cache::get_or_set($key, $callback);

		$this->assertEquals($cached_value, $result);
	}

	/**
	 * Test cache get or set cache miss.
	 */
	public function test_cache_get_or_set_cache_miss(): void
	{
		$key = 'test_key';
		$fresh_data = 'fresh_data';
		$expiration = HOUR_IN_SECONDS;

		\WP_Mock::userFunction('get_transient')
			->withAnyArgs()
			->andReturn(false);

		\WP_Mock::userFunction('set_transient')
			->withAnyArgs()
			->andReturn(true);

		$callback = function () use ($fresh_data) {
			return $fresh_data;
		};

		$result = Cache::get_or_set($key, $callback, $expiration);

		$this->assertEquals($fresh_data, $result);
        $this->assertEquals(1, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('set_transient'));
	}

	/**
	 * Test cache clear with exact key.
	 */
	public function test_cache_clear_exact_key(): void
	{
		$key = 'exact_key';

		\WP_Mock::userFunction('delete_transient')
			->withAnyArgs()
			->andReturn(true);

		$count = Cache::clear($key);

		$this->assertEquals(1, $count);
        $this->assertEquals(1, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('delete_transient'));
        $args = \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::get_args('delete_transient', 0);
        $this->assertEquals($key, $args[0]);
	}

	/**
	 * Test cache clear with exact key that doesn't exist.
	 */
	public function test_cache_clear_exact_key_not_found(): void
	{
		$key = 'nonexistent_key';

		\WP_Mock::userFunction('delete_transient')
			->withAnyArgs()
			->andReturn(false);

		$count = Cache::clear($key);

		$this->assertEquals(0, $count);
        $this->assertEquals(1, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('delete_transient'));
        $args = \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::get_args('delete_transient', 0);
        $this->assertEquals($key, $args[0]);
	}

	/**
	 * Test cache clear with pattern.
	 */
	public function test_cache_clear_pattern(): void
	{
		global $wpdb;
		$wpdb = \Mockery::mock('\wpdb');
		$wpdb->options = 'wp_options';

		$pattern = 'dynos_*';
		$transients = [
			'_transient_dynos_posts_123',
			'_transient_dynos_terms_456',
		];

		$wpdb->shouldReceive('esc_like')
			->once()
			->with('_transient_')
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
			->once()
			->andReturn("SELECT option_name FROM wp_options WHERE option_name LIKE '_transient_dynos_%'");

		$wpdb->shouldReceive('get_col')
			->once()
			->andReturn($transients);

		\WP_Mock::userFunction('delete_transient')
			->twice()
			->andReturn(true);

		$count = Cache::clear($pattern);

		$this->assertEquals(2, $count);
	}

	/**
	 * Test cache clear all.
	 */
	public function test_cache_clear_all(): void
	{
		global $wpdb;
		$wpdb = \Mockery::mock('\wpdb');
		$wpdb->options = 'wp_options';

		$wpdb->shouldReceive('esc_like')
			->once()
			->with('_transient_')
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
			->once()
			->andReturn("SELECT option_name FROM wp_options WHERE option_name LIKE '_transient_dynos_%'");

		$wpdb->shouldReceive('get_col')
			->once()
			->andReturn(['_transient_dynos_test']);

		\WP_Mock::userFunction('delete_transient')
			->once()
			->andReturn(true);

		$count = Cache::clear_all();

		$this->assertEquals(1, $count);
	}

	/**
	 * Test get cached terms.
	 */
	public function test_cache_get_terms(): void
	{
		$taxonomy = 'category';
		$args = ['hide_empty' => false];
		$terms = [
			(object) ['term_id' => 1, 'name' => 'Term 1'],
			(object) ['term_id' => 2, 'name' => 'Term 2'],
		];

		\WP_Mock::userFunction('wp_json_encode')
			->once()
			->with($args)
			->andReturn(json_encode($args));

		\WP_Mock::userFunction('get_transient')
			->once()
			->andReturn(false);

		\WP_Mock::userFunction('get_terms')
			->once()
			->andReturn($terms);

		\WP_Mock::userFunction('set_transient')
			->once()
			->andReturn(true);

		$result = Cache::get_terms($taxonomy, $args);

		$this->assertEquals($terms, $result);
	}

	/**
	 * Test get cached posts.
	 */
	public function test_cache_get_posts(): void
	{
		$args = ['post_type' => 'post', 'posts_per_page' => 10];
		$post_ids = [1, 2, 3];

		\WP_Mock::userFunction('wp_json_encode')
			->withAnyArgs()
			->andReturn(json_encode($args));

		\WP_Mock::userFunction('get_transient')
			->withAnyArgs() // allow cache key
			->andReturn(false);

		// Inject posts into global WP_Query stub
        \WP_Query::$injected_posts = [
            (object) ['ID' => 1],
            (object) ['ID' => 2],
            (object) ['ID' => 3],
        ];

		\WP_Mock::userFunction('wp_list_pluck')
			->withAnyArgs() // list, field
			->andReturn($post_ids);

		\WP_Mock::userFunction('set_transient')
			->withAnyArgs()
			->andReturn(true);

		$result = Cache::get_posts($args);

		// Verify set_transient was called
        $this->assertEquals(1, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('set_transient'));

        // Verify WP_Query construction
        $this->assertEquals(2, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('WP_Query::__construct'));
        // First for getting IDs (in callback), second for returning object
	}

	/**
	 * Test cache clear on post save.
	 */
	public function test_cache_clear_on_post_save(): void
	{
		global $wpdb;
		$wpdb = \Mockery::mock('\wpdb');
		$wpdb->options = 'wp_options';

		$post_id = 123;

		\WP_Mock::userFunction('get_post_type')
			->withAnyArgs()
			->andReturn('post');

		// Simulate quick edit save to trigger get_post_type
        $_POST['action'] = 'inline-save';
        $_POST['_inline_edit'] = true;

		\WP_Mock::userFunction('get_object_taxonomies')
			->withAnyArgs()
			->andReturn(['category', 'post_tag']);

		// Mock pattern clearing for posts - called 2 times (for helper logic internally)
		$wpdb->shouldReceive('esc_like')
            ->atLeast()
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
            ->atLeast()
			->andReturn("SELECT option_name FROM wp_options");

		$wpdb->shouldReceive('get_col')
            ->atLeast()
			->andReturn([]);

        // delete_transient needs to be stubbed or Spy will count it.
        // Cache::clear calls it if results found. Mock returns [] so loop doesn't run.
        // But get_post_type etc are called.

		Cache::clear_on_post_save($post_id);

		$this->assertTrue(true); // Function executed without error
        $this->assertEquals(1, \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::count('get_post_type'));
	}

	/**
	 * Test cache clear on term save.
	 */
	public function test_cache_clear_on_term_save(): void
	{
		global $wpdb;
		$wpdb = \Mockery::mock('\wpdb');
		$wpdb->options = 'wp_options';

		$term_id = 456;

		// Mock pattern clearing
		$wpdb->shouldReceive('esc_like')
            ->atLeast()
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
            ->atLeast()
			->andReturn("SELECT option_name FROM wp_options");

		$wpdb->shouldReceive('get_col')
            ->atLeast()
			->andReturn([]);

		Cache::clear_on_term_save($term_id);

		$this->assertTrue(true); // Function executed without error
	}

	/**
	 * Test cache clear on settings update.
	 */
	public function test_cache_clear_on_settings_update(): void
	{
		global $wpdb;
		$wpdb = \Mockery::mock('\wpdb');
		$wpdb->options = 'wp_options';

		$wpdb->shouldReceive('esc_like')
			->once()
			->with('_transient_')
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
			->once()
			->andReturn("SELECT option_name FROM wp_options");

		$wpdb->shouldReceive('get_col')
			->once()
			->andReturn([]);

		Cache::clear_on_settings_update();

		$this->assertTrue(true); // Function executed without error
	}
}
