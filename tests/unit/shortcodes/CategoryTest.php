<?php

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use TechmireSolutions\DynamicOnlineServices\Shortcodes\Category;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class CategoryTest extends TestCase
{
    public function setUp(): void
    {
        WP_Mock::setUp();
        // Global functions needed for all tests
        WP_Mock::userFunction('remove_shortcode', ['return' => true]);
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /**
     * Test initialization.
     */
    public function test_init()
    {
        WP_Mock::userFunction('add_shortcode', [
            'times' => 1,
            'args' => [Category::TAG, [Category::class, 'render_callback']],
        ]);

        Category::init();
        $this->assertTrue(true);
    }

    /**
     * Test render callback instantiates and calls render.
     */
    public function test_render_callback()
    {
        // Mock dependencies for render
        WP_Mock::userFunction('shortcode_atts', [
            'return' => ['posts_per_page' => 10],
        ]);

        WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Shortcodes\is_tax', [
            'return' => false,
        ]);

        // Fix: Mock get_query_var which is called in default args
        WP_Mock::userFunction('get_query_var', [
            'return' => 1,
        ]);

        // Since we can't easily mock the 'new self()' inside static method without more complex DI,
        // we'll just verify basic execution flow hits the early exit.

        $result = Category::render_callback([]);
        $this->assertEquals('', $result);
    }

    /**
     * Test render method with valid term.
     */
    public function test_render_success()
    {
        $category = new Category();
        $term = \Mockery::mock('\WP_Term');

        // Mocks for environment
        WP_Mock::userFunction('shortcode_atts', [
            'return' => ['hide_empty' => false, 'pagination' => false, 'posts_per_page' => 5],
        ]);

        WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Shortcodes\is_tax', [
            'return' => true,
        ]);

        // Mock get_query_var for paged arg
        WP_Mock::userFunction('get_query_var', [
            'return' => 1,
        ]);

        WP_Mock::userFunction('get_queried_object', [
            'return' => $term,
        ]);

        WP_Mock::userFunction('doc_validate_category_shortcode_attributes', [
            'return' => ['hide_empty' => false, 'pagination' => false, 'posts_per_page' => 5],
        ]);
        WP_Mock::userFunction('doc_validate_term_object', [
            'return' => $term,
        ]);
        WP_Mock::userFunction('doc_enqueue_service_card_styles_asset', [
            'times' => 1,
        ]);

        // Mock Filters - Must use with() to reply()
        WP_Mock::onFilter('doc_category_content_term')
            ->with($term)
            ->reply($term);

        // doc_category_content_items receives (merged_items, term)
        WP_Mock::onFilter('doc_category_content_items')
            ->with([], $term)
            ->reply([]);

        // doc_category_content_output receives (final_output, items, term)
        // final_output = 'Rendered Items' + '' (pagination)
        WP_Mock::onFilter('doc_category_content_output')
            ->with('Rendered Items', [], $term)
            ->reply('Final Output');

        // Mock Data Retrieval Functions
        WP_Mock::userFunction('doc_get_category_shortcode_child_categories', [
            'return' => [],
        ]);
        WP_Mock::userFunction('doc_get_category_shortcode_services', [
            'return' => ['items' => [], 'total_pages' => 1, 'current_page' => 1],
        ]);
        WP_Mock::userFunction('doc_render_category_shortcode_items', [
            'return' => 'Rendered Items',
        ]);

        // Mock doc_get_pagination_html since it is checked in load_dependencies
        WP_Mock::userFunction('doc_get_pagination_html', [
            'return' => '',
        ]);

        // Execute
        $output = $category->render([]);

        // Verify
        $this->assertEquals('Final Output', $output);
    }
}
