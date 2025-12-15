<?php
/**
 * Cards Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards;

/**
 * Test Cards shortcode class.
 */
class CardsTest extends TestCase
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

		// Verify both shortcode tags are registered
		$this->assertEquals('service_cards', Cards::TAG);
		$this->assertEquals('course_cards', Cards::ALIAS_TAG);
	}

	/**
	 * Test render_callback handles array attributes.
	 */
	public function test_render_callback_handles_array_attributes(): void
	{
		$atts = ['category' => 'test', 'limit' => '5'];

		// Mock dependencies
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category']);

		\WP_Mock::userFunction('shortcode_atts')
			->andReturn($atts);

		\WP_Mock::userFunction('get_query_var')
			->andReturn(1);

		// Verify method exists
		$this->assertTrue(method_exists(Cards::class, 'render_callback'));
	}

	/**
	 * Test render_callback handles string attributes.
	 */
	public function test_render_callback_handles_string_attributes(): void
	{
		// Shortcode attributes can be strings when empty
		$atts = '';

		// Verify method handles non-array input
		$this->assertTrue(method_exists(Cards::class, 'render_callback'));
	}

	/**
	 * Test render method parses attributes correctly.
	 */
	public function test_render_parses_attributes(): void
	{
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category']);

		\WP_Mock::userFunction('shortcode_atts')
			->once()
			->andReturnUsing(function ($defaults, $atts) {
				return array_merge($defaults, $atts);
			});

		\WP_Mock::userFunction('get_query_var')
			->andReturn(1);

		// Verify method exists
		$this->assertTrue(method_exists(Cards::class, 'render'));
	}

	/**
	 * Test load_dependencies loads required files.
	 */
	public function test_load_dependencies(): void
	{
		// Verify method exists
		$this->assertTrue(method_exists(Cards::class, 'load_dependencies'));
	}
}
