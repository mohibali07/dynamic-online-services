<?php

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * FAQs Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use WP_Mock\Tools\TestCase;

/**
 * Test FAQs shortcode class.
 */
class FaqsTest extends TestCase
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
        \WP_Mock::userFunction('current_user_can', ['return' => false]);
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
	 * Test init registers shortcode.
	 */
	public function test_init_registers_shortcode(): void
	{
		\WP_Mock::userFunction('add_shortcode', [
			'times' => 1,
			'args' => ['service_faqs_accordion', [\TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::class, 'render']],
		]);

		\TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::init();
		$this->assertTrue(true);
	}

	/**
	 * Test render returns empty on wrong context.
	 */
	public function test_render_wrong_context(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		$result = \TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::render([]);
		$this->assertEquals('', $result);
	}

	/**
	 * Test render handles empty FAQs.
	 */
	public function test_render_empty_faqs(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(true);

		\WP_Mock::userFunction('get_queried_object')
			->andReturn((object)['ID' => 123, 'post_type' => 'services']);

        // Mock dynos_get_service_faqs
        \WP_Mock::userFunction('dynos_get_service_faqs', [
            'return' => []
        ]);

		\WP_Mock::onFilter('dynos_faqs_empty_content')
			->with('', 123)
			->reply('');

		$result = \TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::render([]);
		$this->assertEquals('', $result);
	}
}
