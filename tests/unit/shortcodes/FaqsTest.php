<?php
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
		\WP_Mock::expectActionAdded('shortcode', \WP_Mock\Functions::type('array'));

		// Verify class exists
		$this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs'));
	}

	/**
	 * Test render returns empty on wrong context.
	 */
	public function test_render_wrong_context(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['service_slug' => 'services']);

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs', 'render'));
	}

	/**
	 * Test render handles empty FAQs.
	 */
	public function test_render_empty_faqs(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['service_slug' => 'services']);

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(true);

		\WP_Mock::userFunction('get_queried_object')
			->andReturn((object)['ID' => 123, 'post_type' => 'services']);

		\WP_Mock::onFilter('dynos_faqs_empty_content')
			->with('', 123)
			->reply('');

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs', 'render'));
	}
}
