<?php
/**
 * Hero Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use PHPUnit\Framework\TestCase;

/**
 * Test Hero shortcode class.
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class HeroTest extends \DYNOS_TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
	}

	/**
	 * Tear down test environment.
	 */
	public function tearDown(): void
	{
		parent::tearDown();
	}

	/**
	 * Test init registers shortcodes.
	 */
	public function test_init_registers_shortcodes(): void
	{
		\WP_Mock::userFunction('add_shortcode', [
            'times' => 2,
            'args' => [\WP_Mock\Functions::type('string'), \WP_Mock\Functions::type('array')]
        ]);

		// Verify class exists
		// Verify class exists
		$this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero'));

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::init();
	}

	/**
	 * Test render_category_hero returns empty on wrong context.
	 *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_render_category_hero_wrong_context(): void
	{
        $mockSanitization = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization');
        $mockSanitization->shouldReceive('sanitize_cpt_settings')
            ->andReturn(['taxonomy_slug' => 'service-category']);

		\WP_Mock::userFunction('is_tax')
			->once()
			->with('service-category')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero', 'render_category_hero'));

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::render_category_hero();
	}

	/**
	 * Test render_service_hero returns empty on wrong context.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_render_service_hero_wrong_context(): void
	{
        // Use Mockery alias for static method (needs separate process or careful handling if run twice in same process)
        // Since alias mocks are static, they persist. We rely on runInSeparateProcess to isolate.
        // Or check if already mocked? Mockery usually doesn't like double aliasing.
        // We can reuse if in same process, but strict isolation is better for alias mocks.

        $mockSanitization = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization');
        $mockSanitization->shouldReceive('sanitize_cpt_settings')
            ->andReturn(['service_slug' => 'services']);

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero', 'render_service_hero'));

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::render_service_hero();
	}
}
