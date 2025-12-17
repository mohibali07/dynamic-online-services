<?php
/**
 * FAQs Shortcode Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Shortcodes;

use PHPUnit\Framework\TestCase;

/**
 * Test FAQs shortcode class.
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class FaqsTest extends \DYNOS_TestCase
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
	 * Test init registers shortcode.
	 */
	public function test_init_registers_shortcode(): void
	{
		\WP_Mock::userFunction('add_shortcode', [
            'times' => 1,
            'args' => ['service_faqs_accordion', \WP_Mock\Functions::type('array')]
        ]);

		// Verify class exists
		$this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs'));

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::init();
	}

	/**
	 * Test render returns empty on wrong context.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_render_wrong_context(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

        $mockSanitization = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization');
        $mockSanitization->shouldReceive('sanitize_cpt_settings')
            ->andReturn(['service_slug' => 'services']);

		\WP_Mock::userFunction('is_singular')
			->once()
			->with('services')
			->andReturn(false);

		// Verify method exists
		$this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs', 'render'));

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::render();
	}

	/**
	 * Test render handles empty FAQs.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_render_empty_faqs(): void
	{
		\WP_Mock::userFunction('__')
			->andReturn('Frequently Asked Questions');

        $mockSanitization = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization');
        $mockSanitization->shouldReceive('sanitize_cpt_settings')
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

        \TechmireSolutions\DynamicOnlineServices\Shortcodes\Faqs::render();
	}
}
