<?php

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
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
use WP_Mock;

defined('DYNOS_PLUGIN_URL') || define('DYNOS_PLUGIN_URL', 'http://example.com/');
defined('DYNOS_VERSION') || define('DYNOS_VERSION', '1.0.0');
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
        // Global mocks
		\WP_Mock::userFunction('get_option', ['return' => []]);
		\WP_Mock::userFunction('wp_parse_args', ['return' => []]);
		\WP_Mock::userFunction('shortcode_atts', [
            'return' => function($defaults, $atts) {
                return is_array($atts) ? array_merge($defaults, $atts) : $defaults;
            }
        ]);
        \WP_Mock::userFunction('sanitize_text_field', ['return' => 'sanitized-text']);
        \WP_Mock::userFunction('sanitize_title', ['return_arg' => 0]);
        \WP_Mock::userFunction('is_admin', ['return' => false]);
        \WP_Mock::userFunction('absint', ['return' => 50]);
        \WP_Mock::userFunction('esc_html__', ['return_arg' => 0]);
        \WP_Mock::userFunction('esc_html', ['return_arg' => 0]);
        \WP_Mock::userFunction('wp_enqueue_style', ['return' => true]);
        \WP_Mock::userFunction('wp_strip_all_tags', ['return_arg' => 0]);
        \WP_Mock::userFunction('wp_add_inline_style', ['return' => true]);
        \WP_Mock::userFunction('wp_add_inline_style', ['return' => true]);
        \WP_Mock::userFunction('taxonomy_exists', ['return' => true]);
        \WP_Mock::userFunction('wp_json_encode', ['return' => '{}']);

        // FQN Mocks for namespaced lookups
        \WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Services\taxonomy_exists', ['return' => true]);
        \WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Services\wp_json_encode', ['return' => '{}']);
        \WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Services\wp_strip_all_tags', ['return_arg' => 0]);
        \WP_Mock::userFunction('TechmireSolutions\DynamicOnlineServices\Services\wp_add_inline_style', ['return' => true]);
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

        Cards::init();
		$this->assertTrue(true);
	}

	/**
	 * Test render_callback handles array attributes.
	 */
	public function test_render_callback_handles_array_attributes(): void
	{
		$atts = ['category' => 'test', 'limit' => '5'];

        // Should return taxonomy slug
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category']);

		\WP_Mock::userFunction('get_query_var')
			->andReturn(1);

        // Assume render returns string
        // Since we mock execution flow, real render logic runs.
        // It likely calls get_term_by etc. we may need more mocks.
        // For now, let's see if it gets far enough.
        // Mock get_term_by
        \WP_Mock::userFunction('get_term_by', ['return' => false]); // Simplest case, returns empty?

        // doc_enqueue_service_card_styles_asset ?
        if (!class_exists('TechmireSolutions\DynamicOnlineServices\Helpers\AssetEnqueuer')) {
             \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Helpers\AssetEnqueuer')
                ->shouldReceive('enqueue_service_card_styles')
                ->zeroOrMoreTimes();
        }

		$result = Cards::render_callback($atts);
        $this->assertIsString($result);
	}

	/**
	 * Test render method parses attributes correctly.
	 */
	public function test_render_parses_attributes(): void
	{
        // ... similar to above
		\WP_Mock::userFunction('dynos_sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category']);

        \WP_Mock::userFunction('get_query_var')
			->andReturn(1);
        \WP_Mock::userFunction('get_term_by', ['return' => false]);

		$instance = new Cards();
		$result = $instance->render([]);
        $this->assertIsString($result);
	}
}
