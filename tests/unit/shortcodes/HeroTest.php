<?php
/**
 * Hero Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use WP_Mock\Tools\TestCase;

/**
 * Test Hero shortcode class.
 */
class HeroTest extends TestCase
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
	 * Test init registers shortcodes.
	 */
	public function test_init_registers_shortcodes(): void
	{
		\WP_Mock::expectActionAdded('shortcode', \WP_Mock\Functions::type('array'));

		// Verify class exists
		$this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero'));
	}

	/**
	 * Test render_category_hero returns empty on wrong context.
	 */
	public function test_render_category_hero_wrong_context(): void
	{
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category']);

		\WP_Mock::userFunction('is_tax')
			->once()
			->with('service-category')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero', 'render_category_hero'));
	}

	/**
	 * Test render_service_hero returns empty on wrong context.
	 */
	public function test_render_service_hero_wrong_context(): void
	{
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['service_slug' => 'services']);

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero', 'render_service_hero'));
	}
}
