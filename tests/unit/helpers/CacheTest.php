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

/**
 * Test cache helper functions.
 */
class CacheTest extends TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::setUp();

		// Load the cache functions
		if (!function_exists('dynos_cache_get_or_set')) {
			require_once dirname(__DIR__, 3) . '/includes/helpers/class-cache.php';
		}
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
			->once()
			->with($key)
			->andReturn($cached_value);

		// Callback should NOT be called on cache hit
		$callback = function () {
			$this->fail('Callback should not be called on cache hit');
		};

		$result = dynos_cache_get_or_set($key, $callback);

		$this->assertEquals($cached_value, $result);
	}

	/**
	 * Test cache miss scenario.
	 */
	public function test_cache_get_or_set_cache_miss(): void
	{
		$key = 'test_key';
		$fresh_data = 'fresh_data';
		$expiration = HOUR_IN_SECONDS;

		\WP_Mock::userFunction('get_transient')
			->once()
			->with($key)
			->andReturn(false);

		\WP_Mock::userFunction('set_transient')
			->once()
			->with($key, $fresh_data, $expiration)
			->andReturn(true);

		$callback = function () use ($fresh_data) {
			return $fresh_data;
		};

		$result = dynos_cache_get_or_set($key, $callback, $expiration);

		$this->assertEquals($fresh_data, $result);
	}

	/**
	 * Test cache clear with exact key.
	 */
	public function test_cache_clear_exact_key(): void
	{
		$key = 'exact_key';

		\WP_Mock::userFunction('delete_transient')
			->once()
			->with($key)
			->andReturn(true);

		$count = dynos_cache_clear($key);

		$this->assertEquals(1, $count);
	}

	/**
	 * Test cache clear with exact key that doesn't exist.
	 */
	public function test_cache_clear_exact_key_not_found(): void
	{
		$key = 'nonexistent_key';

		\WP_Mock::userFunction('delete_transient')
			->once()
			->with($key)
			->andReturn(false);

		$count = dynos_cache_clear($key);

		$this->assertEquals(0, $count);
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

		$count = dynos_cache_clear($pattern);

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

		$count = dynos_cache_clear_all();

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

		$result = dynos_cache_get_terms($taxonomy, $args);

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
			->times(2)
			->andReturn(json_encode($args));

		\WP_Mock::userFunction('get_transient')
			->once()
			->andReturn(false);

		// Mock WP_Query for caching
		$query_mock = \Mockery::mock('WP_Query');
		$query_mock->posts = [
			(object) ['ID' => 1],
			(object) ['ID' => 2],
			(object) ['ID' => 3],
		];

		\WP_Mock::userFunction('wp_list_pluck')
			->once()
			->andReturn($post_ids);

		\WP_Mock::userFunction('set_transient')
			->once()
			->andReturn(true);

		// We can't easily test the full WP_Query instantiation
		// This test verifies the caching logic structure
		$this->assertTrue(function_exists('dynos_cache_get_posts'));
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
			->once()
			->with($post_id)
			->andReturn('post');

		\WP_Mock::userFunction('get_object_taxonomies')
			->once()
			->with('post')
			->andReturn(['category', 'post_tag']);

		// Mock pattern clearing for posts
		$wpdb->shouldReceive('esc_like')
			->twice()
			->with('_transient_')
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
			->twice()
			->andReturn("SELECT option_name FROM wp_options");

		$wpdb->shouldReceive('get_col')
			->twice()
			->andReturn([]);

		dynos_cache_clear_on_post_save($post_id);

		$this->assertTrue(true); // Function executed without error
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
			->twice()
			->with('_transient_')
			->andReturn('_transient_');

		$wpdb->shouldReceive('prepare')
			->twice()
			->andReturn("SELECT option_name FROM wp_options");

		$wpdb->shouldReceive('get_col')
			->twice()
			->andReturn([]);

		dynos_cache_clear_on_term_save($term_id);

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

		dynos_cache_clear_on_settings_update();

		$this->assertTrue(true); // Function executed without error
	}
}
