<?php

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Post Type Registration Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Cpt\ServicePostType;

require_once dirname(__DIR__, 2) . '/cpt/class-service-post-type.php';

/**
 * Test ServicePostType class.
 */
class PostTypeTest extends TestCase
{
    /**
     * Set up test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();
        \WP_Mock::userFunction('get_option')->andReturn([]);
        \WP_Mock::userFunction('wp_parse_args')->andReturnArg(0);
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
     * Test post type instantiation.
     */
    public function test_can_instantiate_post_type(): void
    {
        $post_type = new ServicePostType('services');
        $this->assertInstanceOf(ServicePostType::class, $post_type);
    }

    /**
     * Test register method adds action hook.
     */
    public function test_register_adds_init_hook(): void
    {
        \WP_Mock::expectActionAdded('init', [\Mockery::type(ServicePostType::class), 'register_post_type']);
        \WP_Mock::expectFilterAdded('post_updated_messages', [\Mockery::type(ServicePostType::class), 'updated_messages']);

        $post_type = new ServicePostType('services');
        $post_type->register();

        $this->assertConditionsMet();
    }

    /**
     * Test post type registration calls WordPress function.
     */
    public function test_register_post_type_calls_wordpress_function(): void
    {
        \WP_Mock::userFunction('_x')
            ->andReturn('Services');

        \WP_Mock::userFunction('__')
            ->andReturn('Service');

        \WP_Mock::userFunction('register_post_type')
            ->once()
            ->with('services', \WP_Mock\Functions::type('array'));

        $post_type = new ServicePostType('services');
        $post_type->register_post_type();

        $this->assertConditionsMet();
    }

    /**
     * Test updated messages returns array.
     */
    public function test_updated_messages_returns_array(): void
    {
        \WP_Mock::userFunction('get_post')
            ->andReturn((object) ['ID' => 1, 'post_date' => '2025-01-01 00:00:00']);

        \WP_Mock::userFunction('get_post_type_object')
            ->with('services')
            ->andReturn((object) ['name' => 'services']);

        \WP_Mock::userFunction('__')
            ->andReturn('Service updated.');

        \WP_Mock::userFunction('__')
            ->andReturn('Service updated.');

        \WP_Mock::userFunction('wp_post_revision_title')
            ->andReturn('Revision');

        \WP_Mock::userFunction('date_i18n')
            ->andReturn('Jan 1, 2025');

        $post_type = new ServicePostType('services');
        $messages = $post_type->updated_messages([]);

        $this->assertIsArray($messages);
        $this->assertArrayHasKey('services', $messages);
    }

    /**
     * Test updated messages sanitizes revision ID.
     */
    public function test_updated_messages_sanitizes_revision_id(): void
    {
        $_GET['revision'] = '123abc'; // Malicious input

        \WP_Mock::userFunction('get_post')
            ->andReturn((object) ['ID' => 1, 'post_date' => '2025-01-01 00:00:00']);

        \WP_Mock::userFunction('get_post_type_object')
            ->with('services')
            ->andReturn((object) ['name' => 'services']);

        \WP_Mock::userFunction('__')
            ->andReturn('Service updated.');

        \WP_Mock::userFunction('__')
            ->andReturn('Service updated.');

        \WP_Mock::userFunction('wp_post_revision_title')
            ->with(123, false) // Should be sanitized to 123
            ->andReturn('Revision');

        \WP_Mock::userFunction('date_i18n')
            ->andReturn('Jan 1, 2025');

        $post_type = new ServicePostType('services');
        $messages = $post_type->updated_messages([]);

        $this->assertIsArray($messages);

        unset($_GET['revision']);
    }
}
