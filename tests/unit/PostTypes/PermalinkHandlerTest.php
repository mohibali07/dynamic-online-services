<?php
/**
 * Permalink Handler Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

namespace TechmireSolutions\DynamicOnlineServices\Tests\PostTypes;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\PostTypes\PermalinkHandler;
use WP_Mock;
use Mockery;
use stdClass;

/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class PermalinkHandlerTest extends DYNOS_TestCase {

	public function setUp(): void {
		parent::setUp();

		// Mock Sanitization class
		$this->mockSanitization();
	}

	private function mockSanitization() {
		// Create a mock for the static method sanitize_cpt_settings
		// Since we can't easily mock static methods of other classes without using
		// aspect mocking or similar, we'll use Mockery if the class is aliasable
		// or rely on a wrapper. However, looking at the code, it calls Sanitization::sanitize_cpt_settings() directly.
		// For this test, we might need to assume a default response or use a alias mock if possible.
		// Given the constraints, let's see if we can use Mockery's alias capability or if we defined a globalstub.

		// In a real strict unit test without alias mocking, we'd need to refactor the dependency.
		// However, for WP code, we often rely on the fact that we can define the class if it doesn't exist.
		// Since Sanitization class is already loaded, we can't redefine it.
		// We'll have to rely on Mockery's 'overload' prefix or just mock the return values if possible.

		// Actually, let's try to mock the class using Mockery alias if it wasn't loaded,
		// but since we loaded dependencies in bootstrap, we might check if we can simply
		// mock the return of the method if it was instance based. It is static.

		// Wait, in previous tests we saw Sanitization usage.
		// Let's check if we can overload it.
		// If not, we might need to mock get_option if Sanitization uses it.
		// Let's assume for now we can mock the underlying get_option which Sanitization likely uses,
		// OR we can proceed and see if it fails.

		// A better approach for this legacy code structure is to define the Sanitization class in bootstrap
		// if it's not real, but it IS real.

		// Let's try to use Mockery to intercept the static call.
		Mockery::mock( 'alias:TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization' )
			->shouldReceive( 'sanitize_cpt_settings' )
			->andReturn( [
				'service_slug' => 'service',
				'taxonomy_slug' => 'services_category',
			] );
	}

	public function tearDown(): void {
		parent::tearDown();
		Mockery::close();
	}

	public function test_filter_post_type_link_valid() {
		$post = Mockery::mock( 'WP_Post' );
		$post->ID = 123;
		$post->post_type = 'service';

		$term = Mockery::mock( 'WP_Term' );
		$term->term_id = 1;
		$term->slug = 'parent-cat';
		$term->parent = 0;
		$term->taxonomy = 'services_category';

		// Setup filters
		WP_Mock::onFilter( 'dynos_service_permalink_before_process' )
			->with( 'http://example.com/service/%services_category%/my-post', $post )
			->reply( 'http://example.com/service/%services_category%/my-post' );

		WP_Mock::onFilter( 'dynos_service_permalink_after_process' )
			->with( 'http://example.com/service/parent-cat/my-post', $post, 'parent-cat' )
			->reply( 'http://example.com/service/parent-cat/my-post' );

		// Mock wp_get_object_terms
		WP_Mock::userFunction( 'wp_get_object_terms', [
			'args' => [ 123, 'services_category', [ 'orderby' => 'parent', 'order' => 'ASC' ] ],
			'return' => [ $term ]
		] );

		// Mock is_wp_error
		WP_Mock::userFunction( 'is_wp_error', [
			'return' => false
		] );

		$link = 'http://example.com/service/%services_category%/my-post';

		$filtered_link = PermalinkHandler::filter_post_type_link( $link, $post );

		$this->assertEquals( 'http://example.com/service/parent-cat/my-post', $filtered_link );
	}

	public function test_filter_post_type_link_nested_terms() {
		$post = Mockery::mock( 'WP_Post' );
		$post->ID = 456;
		$post->post_type = 'service';

		$child_term = Mockery::mock( 'WP_Term' );
		$child_term->term_id = 2;
		$child_term->slug = 'child-cat';
		$child_term->parent = 1;
		$child_term->taxonomy = 'services_category';

		$parent_term = Mockery::mock( 'WP_Term' );
		$parent_term->term_id = 1;
		$parent_term->slug = 'parent-cat';
		$parent_term->parent = 0;
		$parent_term->taxonomy = 'services_category';

		// Mock wp_get_object_terms
		WP_Mock::userFunction( 'wp_get_object_terms', [
			'return' => [ $child_term ]
		] );

		// Mock get_term
		WP_Mock::userFunction( 'get_term', [
			'args' => [ 1, 'services_category' ],
			'return' => $parent_term
		] );

		WP_Mock::userFunction( 'is_wp_error', [ 'return' => false ] );

		// Setup filters to passthrough
		// WP_Mock by default returns the first argument for apply_filters, so we don't need explicit mocks for passthrough

		$link = 'http://example.com/service/%services_category%/child-post';

		$filtered_link = PermalinkHandler::filter_post_type_link( $link, $post );

		$this->assertEquals( 'http://example.com/service/parent-cat/child-cat/child-post', $filtered_link );
	}

	public function test_filter_post_type_link_no_terms() {
		$post = Mockery::mock( 'WP_Post' );
		$post->ID = 789;
		$post->post_type = 'service';

		WP_Mock::userFunction( 'wp_get_object_terms', [
			'return' => [] // No terms
		] );

		WP_Mock::userFunction( 'is_wp_error', [ 'return' => false ] );

		// Filters pass through by default in WP_Mock if not mocked

		$link = 'http://example.com/service/%services_category%/no-term-post';

		$filtered_link = PermalinkHandler::filter_post_type_link( $link, $post );

		// Should remove the placeholder with trailing slash if present, per code implementation
		// Code: $post_link = str_replace( '%services_category%/', '', $post_link );
		$this->assertEquals( 'http://example.com/service/no-term-post', $filtered_link );
	}

	public function test_build_category_hierarchy_path_circular_reference() {
		$term = Mockery::mock( 'WP_Term' );
		$term->term_id = 1;
		$term->slug = 'circular';
		$term->parent = 1; // Points to self
		$term->taxonomy = 'services_category';

		// Since build_category_hierarchy_path calls log_error which checks function_exists 'dynos_log_error'
		WP_Mock::userFunction( 'dynos_log_error', [
			'times' => 1
		] );

		WP_Mock::expectAction( 'dynos_permalink_self_reference', 1 );

		$path = PermalinkHandler::build_category_hierarchy_path( $term, 'services_category' );

		$this->assertEquals( 'circular', $path, 'Should return just the slug if self-reference detected' );
	}
}
