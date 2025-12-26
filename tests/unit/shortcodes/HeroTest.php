<?php
declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Hero Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

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
		// Global mocks
		\WP_Mock::userFunction('get_option', ['return' => []]);
		\WP_Mock::userFunction('wp_parse_args', ['return' => []]);
		\WP_Mock::userFunction('get_transient', ['return' => false]);
		\WP_Mock::userFunction('set_transient', ['return' => true]);
		\WP_Mock::userFunction('sanitize_title', ['return_arg' => 0]);
        \WP_Mock::userFunction('sanitize_text_field', ['return' => 'sanitized-text']);
        \WP_Mock::userFunction('is_admin', ['return' => false]);
        \WP_Mock::userFunction('absint', ['return' => 50]);
        \WP_Mock::userFunction('esc_html__', ['return_arg' => 0]);
        \WP_Mock::userFunction('esc_html', ['return_arg' => 0]);
        \WP_Mock::userFunction('shortcode_atts', [
            'return' => function($defaults, $atts) {
                return is_array($atts) ? array_merge($defaults, $atts) : $defaults;
            }
        ]);
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
		\WP_Mock::userFunction('add_shortcode')
			->times(2);

		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::init();
		$this->assertTrue(true);
	}

	/**
	 * Test render_category_hero returns empty on wrong context.
	 */
	public function test_render_category_hero_wrong_context(): void
	{
        // Default taxonomy_slug is 'services_category'
		\WP_Mock::userFunction('is_tax')
			->once()
			->with('service-category')
			->andReturn(false);

		$result = \TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::render_category_hero([]);
		$this->assertEquals('', $result);
	}

	/**
	 * Test render_service_hero returns empty on wrong context.
	 */
	public function test_render_service_hero_wrong_context(): void
	{
        // Default service_slug is 'service'
		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		$result = \TechmireSolutions\DynamicOnlineServices\Shortcodes\Hero::render_service_hero([]);
		$this->assertEquals('', $result);
	}
}
