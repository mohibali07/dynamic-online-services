<?php
/**
 * Category Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\Category;
use WP_Mock;

/**
 * Test Category shortcode class.
 */
class CategoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();

        // Global functions needed for all tests
        WP_Mock::userFunction('remove_shortcode', ['return' => true]);

        // Ensure specific calls match using closure
        WP_Mock::userFunction('get_option', [
             'return' => function($option) {
                 if ($option === 'dynos_options') {
                     return ['service_taxonomy_slug' => 'service-category', 'service_slug' => 'services'];
                 }
                 return [];
             }
        ]);
        WP_Mock::userFunction('wp_parse_args', ['return' => []]);
        WP_Mock::userFunction('get_transient', ['return' => false]);
        WP_Mock::userFunction('set_transient', ['return' => true]);
        WP_Mock::userFunction('sanitize_title', ['return_arg' => 0]);
        WP_Mock::userFunction('sanitize_text_field', ['return' => 'sanitized-text']);
        WP_Mock::userFunction('is_admin', ['return' => false]);
        WP_Mock::userFunction('absint', ['return' => 50]);
        WP_Mock::userFunction('esc_html__', ['return_arg' => 0]);
        WP_Mock::userFunction('esc_html', ['return_arg' => 0]);
        WP_Mock::userFunction('is_tax', ['return' => true]);
        WP_Mock::userFunction('get_query_var', ['return' => 'test-category']);
        WP_Mock::userFunction('is_wp_error', ['return' => false]);
        WP_Mock::userFunction('get_posts', ['return' => []]);
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
        parent::tearDown();
    }

    public function test_init_registers_shortcode(): void
    {
        // WP_Mock::expectActionAdded('add_shortcode', ...); // Incorrect usage
        WP_Mock::userFunction('add_shortcode', [
            'times' => 1,
            'args' => ['service_category_content', [Category::class, 'render_callback']],
        ]);

        Category::init();
        $this->assertTrue(true);
    }

    public function test_render_callback(): void
    {
        WP_Mock::userFunction('shortcode_atts', [
            'return' => ['taxonomy_slug' => 'services_category'],
        ]);
        WP_Mock::userFunction('is_tax', [
            'return' => false,
        ]);

        // Stub function calls if they don't exist in environment yet
        if (!function_exists('dynos_validate_category_shortcode_attributes')) {
             WP_Mock::userFunction('dynos_validate_category_shortcode_attributes', ['return_arg' => 0]);
        }

        $result = Category::render_callback([]);
        $this->assertEquals('', $result);
    }

    public function test_render_success(): void
    {
        $term = (object) ['term_id' => 123, 'name' => 'Test Category', 'slug' => 'test-cat'];

        WP_Mock::userFunction('shortcode_atts', [
             'return' => [
                 'posts_per_page' => -1,
                 'hide_empty' => false,
                 'pagination' => false,
                 'columns' => 'auto',
                 'min_width' => '',
                 'paged' => 1,
                 'orderby' => 'menu_order',
                 'order' => 'ASC',
                 'taxonomy_slug' => 'service-category',
             ]
        ]);

        WP_Mock::userFunction('is_tax')
            ->with('service-category')
            ->andReturn(true);

        WP_Mock::userFunction('get_queried_object', [
            'return' => $term,
        ]);

        WP_Mock::userFunction('get_query_var', [
            'return' => 1,
        ]);

        WP_Mock::onFilter('dynos_category_content_term')
            ->with($term)
            ->reply($term);

        // Expect items filter to receive [1] from services mock
        WP_Mock::onFilter('dynos_category_content_items')
            ->with([1], $term)
            ->reply([1]);

        WP_Mock::onFilter('dynos_category_content_output')
            ->with(\Mockery::any(), [1], $term)
            ->reply('Final Output');

        // Note: AssetEnqueuer::enqueue_service_card_styles() will be handled by the stub class at end of file.

        // Functions checked via function_exists in code
        // We don't need to mock them if we don't define them, code handles it (checks exists).
        // But if code checks function_exists and it returns false, it skips logic.
        // We want to test logic?
        // Code: if (function_exists(...)) call it.
        // Since we are mocking userFunction, checking function_exists for mocked function works?
        // WP_Mock doesn't make function_exists return true unless mocked?
        // Actually WP_Mock creates the function if not exists?
        // Yes, userFunction defines it.
        // Note: dynos_render... will be handled by stub
        // Remove function_exists mock to avoid error
        WP_Mock::userFunction('dynos_validate_term_object', ['return' => $term]);
        WP_Mock::userFunction('dynos_get_category_shortcode_child_categories', ['return' => []]);
        WP_Mock::userFunction('dynos_get_category_shortcode_services', ['return' => ['items' => [1], 'total_pages' => 1, 'current_page' => 1]]);
        // dynos_render... stubbed below

        WP_Mock::userFunction('get_queried_object', ['return' => $term]);
        WP_Mock::userFunction('dynos_render_category_shortcode_items', ['return' => 'Final Output']);
        // Also need mock for dynos_validate_category_shortcode_attributes
        WP_Mock::userFunction('dynos_validate_category_shortcode_attributes', ['return_arg' => 0]);

        $output = Category::render_callback([]);

        // Verify
        $this->assertEquals('Final Output', $output);
    }
}

// Global Stubs
