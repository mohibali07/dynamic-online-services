<?php
/**
 * CategoryQueryService Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace {
	// Global stubs moved to bootstrap.php
}

namespace TechmireSolutions\DynamicOnlineServices\Tests\Services {

	use TechmireSolutions\DynamicOnlineServices\Services\CategoryQueryService;
	use WP_Mock;
	use Mockery;
	use stdClass;

	/**
	 * Test CategoryQueryService class.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	class CategoryQueryServiceTest extends \DYNOS_TestCase {

		/**
		 * @var \wpdb|Mockery\LegacyMockInterface|Mockery\MockInterface
		 */
		protected $wpdb;

		public function setUp(): void {
			parent::setUp();
			if (!class_exists('WP_Term')) {
				$mock = \Mockery::mock('alias:WP_Term');
			}
			if ( ! defined( 'DYNOS_PLUGIN_URL' ) ) {
				define( 'DYNOS_PLUGIN_URL', 'http://example.com/wp-content/plugins/dynamic-online-services/' );
			}
			if ( ! defined( 'DYNOS_DEFAULT_EXCERPT_LENGTH' ) ) {
				define( 'DYNOS_DEFAULT_EXCERPT_LENGTH', 20 );
			}
			$this->wpdb = Mockery::mock( '\wpdb' );
			$this->wpdb->termmeta = 'wp_termmeta';
			$GLOBALS['wpdb'] = $this->wpdb;
		}

		public function tearDown(): void {
			parent::tearDown();
			unset( $GLOBALS['wpdb'] );
		}

		    public function test_get_categories_returns_terms() {
	    	$this->markTestSkipped('CategoryQueryService::get_categories() method does not exist. Only get_child_categories() and get_services() are available.');
	    }

		public function test_get_child_categories_success() {
		// Mock Sanitization
		$sanitizationMock = \Mockery::mock('alias:TechmireSolutions\\DynamicOnlineServices\\PostTypes\\Sanitization');
		$sanitizationMock->shouldReceive('sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category', 'service_slug' => 'services'])
			->byDefault();

		$term = \Mockery::mock('WP_Term');
			$term->term_id = 123;
			$term->name = 'Parent Term';
			$term->slug = 'parent-term';

			// Mock Sanitization -> Options -> get_option calls
			WP_Mock::userFunction( 'get_transient', [ 'return' => false ] );
			WP_Mock::userFunction( 'set_transient', [ 'return' => true ] );
			WP_Mock::userFunction( 'maybe_serialize', [
				'return' => function( $data ) {
					return serialize( $data );
				}
			] );
			WP_Mock::userFunction( 'get_option', [
				'return' => [], // Correct: return array for settings
			] );

			WP_Mock::userFunction( 'wp_parse_args', [
				'return' => function( $args, $defaults ) {
					return array_merge( is_array($defaults) ? $defaults : [], is_array($args) ? $args : [] );
				}
			] );

			WP_Mock::userFunction( 'sanitize_title', [ 'returnArg' => 0 ] );

			WP_Mock::userFunction( 'sanitize_text_field', [
				'returnArg' => 0,
			] );

			// Mock get_terms
			$child_term = \Mockery::mock('WP_Term');
			$child_term->term_id = 456;
			$child_term->name = 'Child Category';
			$child_term->description = 'Description';
			$child_term->slug = 'child-category';

			// Code defaults taxonomy to 'services_category' if get_option returns empty.
			// So we must expect 'services_category'.
			WP_Mock::userFunction( 'get_terms', [
				'args' => [ [
					'taxonomy'   => 'service-category',
					'parent'     => 123,
					'hide_empty' => true,
				] ],
				'return' => [ $child_term ],
			] );

			WP_Mock::userFunction( 'absint', [
				'return' => function($a) { return  (int) $a; },
			] );

			WP_Mock::userFunction( 'is_wp_error', [
				'return' => false,
			] );

			// Mock wp_list_pluck
			WP_Mock::userFunction( 'wp_list_pluck', [
				'args' => [ [ $child_term ], 'term_id' ],
				'return' => [ 456 ],
			] );

			// Mock $wpdb->prepare and get_results
			$this->wpdb->shouldReceive( 'prepare' )->once()->andReturn( 'SQL' );

			$meta_row = new stdClass();
			$meta_row->term_id = 456;
			$meta_row->meta_value = '789'; // thumbnail ID

			$this->wpdb->shouldReceive( 'get_results' )->once()->with( 'SQL' )->andReturn( [ $meta_row ] );

			// Mock get_term_link
			WP_Mock::userFunction( 'get_term_link', [
				'args' => [ $child_term ],
				'return' => 'http://example.com/child-category',
			] );

			WP_Mock::userFunction( 'wp_trim_words', [
				'return' => 'Description',
			] );

			$results = CategoryQueryService::get_child_categories( $term, true );

			$this->assertCount( 1, $results );
			$this->assertEquals( 'Child Category', $results[0]['title'] );
			$this->assertEquals( 789, $results[0]['image_id'] );
			$this->assertEquals( 'category', $results[0]['type'] );
		}

		public function test_get_services_simple() {
		// Mock Sanitization
		$sanitizationMock = \Mockery::mock('alias:TechmireSolutions\\DynamicOnlineServices\\PostTypes\\Sanitization');
		$sanitizationMock->shouldReceive('sanitize_cpt_settings')
			->andReturn(['taxonomy_slug' => 'service-category', 'service_slug' => 'services'])
			->byDefault();

		$term = \Mockery::mock('WP_Term');
		$term->term_id = 123;

			$atts = [
				'posts_per_page' => 10,
			];

			WP_Mock::userFunction( 'get_transient', [ 'return' => false ] );
			WP_Mock::userFunction( 'set_transient', [ 'return' => true ] );
			WP_Mock::userFunction( 'maybe_serialize', [
				'return' => function( $data ) {
					return serialize( $data );
				}
			] );
			WP_Mock::userFunction( 'get_option', [ 'return' => 'services' ] );
			WP_Mock::userFunction( 'wp_parse_args', [
				'return' => function( $args, $defaults ) {
					return array_merge( is_array($defaults) ? $defaults : [], is_array($args) ? $args : [] );
				}
			] );
			WP_Mock::userFunction( 'sanitize_title', [ 'returnArg' => 0 ] );
			WP_Mock::userFunction( 'sanitize_text_field', [ 'returnArg' => 0 ] );

			WP_Mock::userFunction( 'absint', [ 'returnArg' => 0 ] );

			// Mock apply_filters
			        WP_Mock::onFilter('dynos_category_content_query_args')
            ->with(\Mockery::type('array'), $term)
            ->reply(function($args) { return $args; });

        WP_Mock::onFilter('dynos_category_content_services')
            ->with(\Mockery::type('array'), $term)
            ->reply([]);
			$post = Mockery::mock( \WP_Post::class );
			$post->ID = 999;
			$post->post_title = 'Test Service';

			WP_Mock::userFunction( 'get_posts', [
				'return' => [ $post ],
			] );

			WP_Mock::onFilter( 'dynos_category_content_services' )
				->with( [ $post ], $term )
				->reply( [ $post ] );

			// Mock Images / ACF logic
			WP_Mock::userFunction( 'get_field', [ 'return' => [] ] ); // Mock ACF function
			WP_Mock::userFunction( 'get_post_thumbnail_id', [ 'return' => false ] );
			WP_Mock::userFunction( 'get_the_excerpt', [ 'return' => 'Service Excerpt' ] );

			WP_Mock::userFunction( 'get_post', [ 'return' => $post ] );
			WP_Mock::userFunction( 'get_the_title', [ 'return' => 'Test Service' ] );
			WP_Mock::userFunction( 'get_permalink', [
				'args' => [ $post ],
				'return' => 'http://example.com/service',
			] );

			WP_Mock::userFunction( 'esc_attr', [
				'return' => function($str) { return (string) $str; }
			] );
			WP_Mock::userFunction( 'esc_url', [ 'returnArg' => 0 ] );
			WP_Mock::userFunction( 'wp_trim_words', [ 'returnArg' => 0 ] );

			$result = CategoryQueryService::get_services( $term, $atts );

			$this->assertCount( 1, $result['items'] );
			$this->assertEquals( 'Test Service', $result['items'][0]['title'] );
			$this->assertEquals( 'post', $result['items'][0]['type'] );
		}
	}
}
