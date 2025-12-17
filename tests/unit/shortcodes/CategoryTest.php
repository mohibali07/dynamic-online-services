<?php

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use TechmireSolutions\DynamicOnlineServices\Shortcodes\Category;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class CategoryTest extends \DYNOS_TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Global functions needed for all tests
        \WP_Mock::userFunction('remove_shortcode', ['return' => true]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
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
    /**
     * @runInSeparateProcess
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
    	/**
	 * Test render method with valid term.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_render_success()
	{
		$category = new Category();
		$term = \Mockery::mock('\WP_Term');

		// Mock dependencies
		\WP_Mock::userFunction('shortcode_atts', [
			'return' => ['hide_empty' => false, 'pagination' => false, 'posts_per_page' => 5],
		]);

		\WP_Mock::userFunction('get_query_var', [
			'return' => 1,
		]);

		\WP_Mock::userFunction('get_queried_object', [
			'return' => $term,
		]);

        // Mock global functions from validation/pagination files if they are not loaded
        if (!function_exists('dynos_validate_category_shortcode_attributes')) {
             \WP_Mock::userFunction('dynos_validate_category_shortcode_attributes')->andReturnArg(0);
        }
        if (!function_exists('dynos_validate_term_object')) {
             \WP_Mock::userFunction('dynos_validate_term_object')->andReturn($term);
        }

        // Mock Sanitization
        $mockSanitization = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization');
        $mockSanitization->shouldReceive('sanitize_cpt_settings')
            ->andReturn(['taxonomy_slug' => 'service-category']);

        \WP_Mock::userFunction('is_tax')
            ->with('service-category')
            ->andReturn(true);

        // Mock AssetEnqueuer
        $mockEnqueuer = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Helpers\AssetEnqueuer');
        $mockEnqueuer->shouldReceive('enqueue_service_card_styles')
            ->once();

		// Mock Filters
		\WP_Mock::onFilter('dynos_category_content_term')
			->with($term)
			->reply($term);

		\WP_Mock::onFilter('dynos_category_content_items')
			->with([], $term)
			->reply([]);

		\WP_Mock::onFilter('dynos_category_content_output')
			->with('Rendered Items', [], $term)
			->reply('Final Output');

		// Mock CategoryQueryService (static)
        $mockQueryService = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Services\CategoryQueryService');
        $mockQueryService->shouldReceive('get_child_categories')
            ->andReturn([]);
        $mockQueryService->shouldReceive('get_services')
            ->andReturn(['items' => [], 'total_pages' => 1, 'current_page' => 1]);

        // Mock CategoryRenderer (instance)
        $rendererMock = \Mockery::mock('overload:TechmireSolutions\DynamicOnlineServices\Renderers\CategoryRenderer');
        $rendererMock->shouldReceive('render')
            ->once()
            ->andReturn('Rendered Items');

		// Execute
		$output = $category->render([]);

		// Verify
		$this->assertEquals('Final Output', $output);
	}
}
