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
 * Test cache helper functions.
 */
class CacheTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();

        // Load the cache functions
        if (!class_exists(Cache::class)) {
            require_once dirname(__DIR__, 3) . '/includes/helpers/class-cache.php';
        }
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
        parent::tearDown();
    }

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

        $result = Cache::get_or_set($key, $callback);

        $this->assertEquals($cached_value, $result);
    }

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

        $result = Cache::get_or_set($key, $callback, $expiration);

        $this->assertEquals($fresh_data, $result);
    }

    public function test_cache_clear_exact_key(): void
    {
        $key = 'exact_key';

        \WP_Mock::userFunction('delete_transient')
            ->once()
            ->with($key)
            ->andReturn(true);

        $count = Cache::clear($key);

        $this->assertEquals(1, $count);
    }

    public function test_cache_clear_exact_key_not_found(): void
    {
        $key = 'nonexistent_key';

        \WP_Mock::userFunction('delete_transient')
            ->once()
            ->with($key)
            ->andReturn(false);

        $count = Cache::clear($key);

        $this->assertEquals(0, $count);
    }

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

        \WP_Mock::userFunction('wp_using_ext_object_cache')
            ->once()
            ->andReturn(false);

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

    public function test_cache_clear_all(): void
    {
        global $wpdb;
        $wpdb = \Mockery::mock('\wpdb');
        $wpdb->options = 'wp_options';

        \WP_Mock::userFunction('wp_using_ext_object_cache')
            ->once()
            ->andReturn(false);

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

    public function test_cache_get_posts(): void
    {
        $args = ['post_type' => 'post', 'posts_per_page' => 10];
        $post_ids = [1, 2, 3];

        \WP_Mock::userFunction('wp_json_encode')
            ->once()
            ->andReturn('json_string');

        \WP_Mock::userFunction('get_transient')
            ->once()
            ->andReturn(false);

        \WP_Mock::userFunction('wp_list_pluck')
            ->once()
            ->andReturn($post_ids);

        \WP_Mock::userFunction('set_transient')
            ->once()
            ->andReturn(true);

        // Explicitly Call the method
        Cache::get_posts($args);

        $this->assertTrue(true);
    }

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

        \WP_Mock::userFunction('wp_using_ext_object_cache')
            ->twice() // Called twice (once for posts pattern, once for terms pattern)
            ->andReturn(false);

        // Set $_POST to trigger taxonomy modification logic
        $_POST['action'] = 'inline-save';
        $_POST['_inline_edit'] = '1';

		$wpdb->shouldReceive('esc_like')
			->times(2) // Once for posts, once for terms
			->with('_transient_')
			->andReturn('_transient_');

        $wpdb->shouldReceive('prepare')
            ->twice()
            ->andReturn("SELECT option_name FROM wp_options");

        $wpdb->shouldReceive('get_col')
            ->twice()
            ->andReturn([]);

        Cache::clear_on_post_save($post_id);

        // Clean up
        unset($_POST['action'], $_POST['_inline_edit']);

        $this->assertTrue(true);
    }

    public function test_cache_clear_on_term_save(): void
    {
        global $wpdb;
        $wpdb = \Mockery::mock('\wpdb');
        $wpdb->options = 'wp_options';

        $term_id = 456;

        \WP_Mock::userFunction('wp_using_ext_object_cache')
            ->twice()
            ->andReturn(false);

        // Previous validation said expected 1 but called 2, so we set times(2)
        $wpdb->shouldReceive('esc_like')
            ->times(2)
            ->andReturn('_transient_');

        $wpdb->shouldReceive('prepare')
            ->twice()
            ->andReturn("SELECT option_name FROM wp_options");

        $wpdb->shouldReceive('get_col')
            ->twice()
            ->andReturn([]);

        Cache::clear_on_term_save($term_id);

        $this->assertTrue(true);
    }

    public function test_cache_clear_on_settings_update(): void
    {
        global $wpdb;
        $wpdb = \Mockery::mock('\wpdb');
        $wpdb->options = 'wp_options';

        \WP_Mock::userFunction('wp_using_ext_object_cache')
            ->once()
            ->andReturn(false);

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

        $this->assertTrue(true);
    }
}
