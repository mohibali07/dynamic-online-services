<?php
/**
 * CategoryRenderer Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Renderers;

use TechmireSolutions\DynamicOnlineServices\Renderers\CategoryRenderer;
use WP_Mock;
use Mockery;

class CategoryRendererTest extends \DYNOS_TestCase {

	public function setUp(): void {
		parent::setUp();
		// Mock common functions
		WP_Mock::userFunction( 'get_transient', [ 'return' => false ] );
		WP_Mock::userFunction( 'set_transient', [ 'return' => true ] );
		WP_Mock::userFunction( 'get_option', [ 'return' => 'services' ] );
		WP_Mock::userFunction( 'maybe_serialize', [
			'return' => function($data) {
				return is_array($data) || is_object($data) ? serialize($data) : $data;
			}
		] );
		WP_Mock::userFunction( 'wp_parse_args', [
			'return' => function($args, $defaults) {
				return array_merge( is_array($defaults) ? $defaults : [], is_array($args) ? $args : [] );
			}
		] );
		WP_Mock::userFunction( '__', [
			'return' => function($s) { return (string)$s; }
		] );
		WP_Mock::userFunction( '_x', [
			'return' => function($s) { return (string)$s; }
		] );
		WP_Mock::userFunction( 'esc_attr__', [
			'return' => function($s) { return (string)$s; }
		] );
		WP_Mock::userFunction( 'esc_html', [
			'return' => function($s) { return (string)$s; }
		] );
		WP_Mock::userFunction( 'esc_attr_e', [
			'return' => function($s) { echo (string)$s; }
		] );

		// Additional mocks needed
		WP_Mock::userFunction( 'wp_kses_post', [
			'return' => function($s) { return (string)$s; }
		] );
		WP_Mock::userFunction( 'wp_attachment_is_image', [ 'return' => true ] );
		WP_Mock::userFunction( 'absint', [
			'return' => function($a) { return (int)$a; }
		] );
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function test_render_with_services_and_categories() {
		// Data setup
		$child_categories = [
			[
				'type'        => 'category',
				'title'       => 'Child Cat',
				'description' => 'Desc',
				'url'         => 'http://example.com/cat',
				'image_id'    => 123,
			]
		];

		$services_data = [
			'items' => [
				[
					'type'        => 'post',
					'title'       => 'Service 1',
					'description' => 'Svc Desc',
					'url'         => 'http://example.com/svc',
					'image_url'   => 'http://example.com/img.jpg',
					'image_alt'   => 'Alt',
				]
			],
			'total_pages' => 2,
			'current_page' => 1,
		];

		$term = new \WP_Term();
		$term->term_id = 99;
		$term->slug = 'parent-slug';

		$atts = [
			'columns' => 3,
			'pagination' => 'true',
		];

		$atts = [
			'columns' => 3,
			'pagination' => 'true',
		];

		// Mock escaping functions
		WP_Mock::userFunction( 'esc_attr', [
			'return' => function($s) { return (string)$s; },
		] );
		WP_Mock::userFunction( 'esc_html', [
			'return' => function($s) { return (string)$s; },
		] );
		WP_Mock::userFunction( 'esc_url', [
			'return' => function($s) { return (string)$s; },
		] );
		WP_Mock::userFunction( 'esc_html__', [
			'returnArg' => 0,
		] );

		// Mock wp_get_attachment_image_url
		WP_Mock::userFunction( 'wp_get_attachment_image_url', [
			'args' => [ 123, 'full' ],
			'return' => 'http://example.com/image.jpg',
		] );

		// Mock pagination functions
		WP_Mock::userFunction( 'paginate_links', [
			'return' => '<div class="pagination">Links</div>',
		] );
		WP_Mock::userFunction( 'add_query_arg', [
			'return' => 'http://example.com/page/2',
		] );
		// get_pagenum_link called inside paginate_links structure usually, or passed in 'base'.
		// Renderer calls paginate_links with args.

		$items = array_merge( $child_categories, $services_data['items'] );
		$renderer = new CategoryRenderer();
		$output = $renderer->render( $items, $term, $atts );

		$this->assertStringContainsString( 'class="service-card-grid"', $output );
		$this->assertStringContainsString( 'Child Cat', $output );
		$this->assertStringContainsString( 'Service 1', $output );
	}

	public function test_render_no_results() {
		$term = new \WP_Term();
		$atts = [];

		WP_Mock::userFunction( 'esc_html__', [ 'returnArg' => 0 ] );
		WP_Mock::userFunction( 'esc_html', [ 'returnArg' => 0 ] );

		$renderer = new CategoryRenderer();
		$output = $renderer->render( [], $term, $atts ); // Corrected: 3 args

		$this->assertStringContainsString( 'No services or sub-categories found', $output );
	}
}
